<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\TestResultInterface;
use App\Entity\Website;
use App\Repository\WebsiteRepository;
use App\Service\WebsiteTesterInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Style\StyleInterface;

abstract class TestRunnerBase extends ContainerAwareCommand
{
    /** @var WebsiteTesterInterface */
    private $tester;

    /** @var WebsiteRepository */
    private $websiteRepository;

    /** @var Swift_Mailer */
    private $mailer;

    public function __construct(WebsiteTesterInterface $tester, WebsiteRepository $websiteRepository, \Swift_Mailer $mailer)
    {
        parent::__construct();
        $this->tester = $tester;
        $this->websiteRepository = $websiteRepository;
        $this->mailer = $mailer;
    }

    final public function run_test(StyleInterface $io)
    {
        $websites = $this->websiteRepository->findAll();
        $tester = $this->get_tester();

        foreach ($websites as $website) {
            $io->text(sprintf('Testing site %1$s', $website->getDomain()));

            $result = $tester
                        ->validate_single_site(
                            $website
                        )
            ;

            if (!$result->get_is_valid()) {
                $this->send_email($website, $result);
            }
        }
    }

    abstract public function get_email_template() : string;
    
    final public function get_tester() : WebsiteTesterInterface
    {
        return $this->tester;
    }

    protected function send_email(Website $website, TestResultInterface $result)
    {
        $message = (new \Swift_Message(sprintf('TLS Cert Problem for %1$s', $website->getDomain())))
        ->setFrom('test@localhost')
        ->setTo('cjhaas@gmail.com')
        ->setBody(
            $this
                ->getContainer()
                ->get('templating')
                ->render(
                    $this->get_email_template(),
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
}
