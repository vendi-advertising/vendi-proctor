<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\WebsiteRepository;
use App\Service\TlsValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Website;
use App\Entity\TlsScanResult;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class TestCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:test';

    private $tlsValidator;

    private $websiteRepository;

    private $mailer;

    public function __construct(TlsValidator $tlsValidator, WebsiteRepository $websiteRepository, \Swift_Mailer $mailer)
    {
        parent::__construct();
        $this->tlsValidator = $tlsValidator;
        $this->websiteRepository = $websiteRepository;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Tester')
        ;
    }

    protected function send_email(Website $website, TlsScanResult $result)
    {
        $message = (new \Swift_Message(sprintf('TLS Cert Problem for %1$s', $website->getDomain())))
        ->setFrom('test@localhost')
        ->setTo('cjhaas@gmail.com')
        ->setBody(
            $this
                ->getContainer()
                ->get('templating')
                ->render(
                    'email/tls-fail.html.twig',
                    [
                        'website' => $website,
                        'result' => $result,
                    ]
                ),
            'text/html'
        )
    ;

        $this->mailer->send($message);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $websites = $this->websiteRepository->findAll();
        foreach ($websites as $website) {
            $io->text(sprintf('Testing site %1$s', $website->getDomain()));

            $result = $this
                        ->tlsValidator
                        ->validate_single_site_tls(
                            $website
                        )
            ;

            if(!$result->getIsValid()){
                $this->send_email($website, $result);
            }
        }

        

        //proctor



        // dump($cert_parts);




        // $io->note('Text');
        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
