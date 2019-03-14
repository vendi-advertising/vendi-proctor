<?php

namespace App\Command;

use App\Repository\WebsiteRepository;
use App\Service\TlsValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';

    private $tlsValidator;

    private $websiteRepository;

    public function __construct(TlsValidator $tlsValidator, WebsiteRepository $websiteRepository)
    {
        parent::__construct();
        $this->tlsValidator = $tlsValidator;
        $this->websiteRepository = $websiteRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Tester')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $websites = $this->websiteRepository->findAll();
        foreach($websites as $website){
            $io->text(sprintf('Testing site %1$s', $website->getDomain()));
            $this
                ->tlsValidator
                ->validate_single_site_tls(
                    $website
                )
            ;
        }



        // dump($cert_parts);




        // $io->note('Text');
        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
