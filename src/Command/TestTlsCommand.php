<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\TlsScanResult;
use App\Entity\Website;
use App\Repository\WebsiteRepository;
use App\Service\TlsValidator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestTlsCommand extends TestRunnerBase
{
    protected static $defaultName = 'app:test:tls';

    public function __construct(TlsValidator $tlsValidator, WebsiteRepository $websiteRepository, \Swift_Mailer $mailer)
    {
        parent::__construct($tlsValidator, $websiteRepository, $mailer);
    }

    protected function configure()
    {
        $this
            ->setDescription('Tester')
        ;
    }

    public function get_email_template() : string
    {
        return 'email/tls-fail.html.twig';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->run_test($io);
    }
}
