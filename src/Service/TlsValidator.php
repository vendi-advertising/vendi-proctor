<?php

declare(strict_types=1);

namespace App\Service;

use App\AgentTests\AgentTestInterface;
use App\AgentTests\Tls\DateValidator;
use App\AgentTests\Tls\DomainNameValidator;
use App\Entity\TestResultInterface;
use App\Entity\TlsScanResult;
use App\Entity\Website;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class TlsValidator implements WebsiteTesterInterface
{
    private $caBundleLoader;
    private $entityManager;
    private $translator;

    public function __construct(CABundleLoader $caBundleLoader, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->caBundleLoader = $caBundleLoader;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public function validate_single_site(Website $website) : TestResultInterface
    {
        return $this->validate_single_site_tls($website);
    }

    protected function validate_single_site_tls(Website $website) : TlsScanResult
    {
        $ca_bundle_filepath = $this->caBundleLoader->get_most_recent_pem_file();

        $result = new TlsScanResult();
        $result->setWebsite($website);

        $hostname = $website->getDomain();
        $ip = $website->getIp();
        $port = $website->getPort();

        //If we didn't supply an IP, manually get it here.
        //We will log that IP address that we used to connect to for auditing.
        if (!$ip) {
            $ip = \gethostbyname($hostname);
        }

        if (!$port) {
            $port = 443;
        }

        $result->setHostnameTested($hostname);
        $result->setIpTested($ip);

        //See https://secure.php.net/manual/en/context.ssl.php
        $stream_options = [
            'ssl' => [
                'peer_name' => $hostname,
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false,
                'capath' => $ca_bundle_filepath,
                'capture_peer_cert' => true,
            ]
        ];

        //Since we use Cloudflare for a lot of things but some clients have their
        //own DNS for internal, we sometimes need to talk directly to the server
        //and cut the proxy out of the loop.
        $url = "ssl://{$ip}:{$port}";

        //Set the PHP docs for more details
        $stream = stream_context_create($stream_options);
        try {
            $client = stream_socket_client($url, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream);
            $context = stream_context_get_params($client);
            //This is what we're most interested in
            $cert = $context['options']['ssl']['peer_certificate'];
            $cert_parts = openssl_x509_parse($cert);
        } catch (\Exception $ex) {
            $result->setFailReason($ex->getMessage());
            $result->set_status_error();

            $cert_parts = null;
        } finally {
            if (isset($client)) {
                fclose($client);
            }
        }

        $result->setRawTlsData($cert_parts);

        if ($cert_parts) {
            $validators = [
                DateValidator::class,
                DomainNameValidator::class,
            ];

            try {
                $statuses = [];

                foreach ($validators as $validator) {

                    //Create a dynamic validator
                    /** @var  AgentTestInterface */
                    $dv = new $validator($cert_parts, $result, $website, $this->translator);

                    //Get the result as a string
                    $local_result = $dv->run_test();

                    //Sanity check the result
                    switch ($local_result) {

                        //Warnings and errors are both exceptions, so grab the message
                        case AgentTestInterface::STATUS_ERROR:
                        case AgentTestInterface::STATUS_WARNING:
                            $result->setFailReason($dv->get_last_exception()->getMessage());
                            break;

                        //NOOP
                        case AgentTestInterface::STATUS_VALID:
                            break;

                        //This is set by the base class as the default but implementations
                        //must always change it
                        case AgentTestInterface::STATUS_UNKNOWN:
                            throw new \Exception('The UNKNOWN status may never be used and must be overriden');

                        //This should never happen so we're guarding against typos pretty much
                        default:
                            throw new \Exception(sprintf('An unsupported status was encountered: %1$s', $local_result));
                    }

                    $statuses[] = $local_result;
                }

                if (in_array(AgentTestInterface::STATUS_ERROR, $statuses)) {
                    $result->set_status_error();
                } elseif (in_array(AgentTestInterface::STATUS_WARNING, $statuses)) {
                    $result->set_status_warning();
                } else {
                    $result->set_status_valid();
                }
            } catch (\Exception $ex) {
                throw new \Exception(
                    'The TLS validator encountered an unhandled exception',
                    0,
                    $ex
                );
            }
        }

        $this->entityManager->persist($result);
        $this->entityManager->flush();

        return $result;
    }
}
