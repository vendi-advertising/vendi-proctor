<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\WebsiteRepository;
use App\Service\UptimeTester;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestUptimeCommand extends TestRunnerBase
{
    protected static $defaultName = 'app:test:uptime';

    public function __construct(UptimeTester $uptimeTester, WebsiteRepository $websiteRepository, \Swift_Mailer $mailer)
    {
        parent::__construct($uptimeTester, $websiteRepository, $mailer);
    }

    protected function configure()
    {
        $this
            ->setDescription('Test uptime')
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
