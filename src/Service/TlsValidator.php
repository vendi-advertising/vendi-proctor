<?php

declare(strict_types=1);

namespace App\Service;

use App\CertificateValidators\CertificateValidatorInterface;
use App\CertificateValidators\DateValidator;
use App\CertificateValidators\DomainNameValidator;
use App\Entity\TlsScanResult;
use App\Entity\Website;
use App\Exception\Tls\CertDateException;
use App\Exception\Tls\CertMissingDataException;
use App\Exception\Tls\CertNotValidException;
use Doctrine\ORM\EntityManagerInterface;

class TlsValidator
{
    private $caBundleLoader;
    private $entityManager;

    public function __construct(CABundleLoader $caBundleLoader, EntityManagerInterface $entityManager)
    {
        $this->caBundleLoader = $caBundleLoader;
        $this->entityManager = $entityManager;
    }

    protected function is_cert_name_valid(array $cert_parts, TlsScanResult $result) : bool
    {
        if(!array_key_exists('subject', $cert_parts)){
            throw CertMissingDataException::create_missing_key('subject');
        }
    }

    public function validate_single_site_tls(Website $website)
    {
        $result = new TlsScanResult();
        $result->setWebsite($website);

        $ca_bundle_filepath = $this->caBundleLoader->get_most_recent_pem_file();

        $hostname = $website->getDomain();
        $ip_or_hostname = $website->getIp();

        //If the direct host isn't supplied, talk directly to the main host
        if(!$ip_or_hostname){
            $ip_or_hostname = $hostname;
        }

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
        $url = "ssl://{$ip_or_hostname}:443";

        //Set the PHP docs for more details
        $stream = stream_context_create($stream_options);
        $client = stream_socket_client($url, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream);
        $context = stream_context_get_params($client);

        //This is what we're most interested in
        $cert = $context['options']['ssl']['peer_certificate'];
        $cert_parts = openssl_x509_parse($cert);

        // dump($cert_parts);

        $result->setRawTlsData($cert_parts);

        $validators = [
            'DateValidator',
            'DomainNameValidator',
        ];

        //The above validators are in this namespace
        $ns = '\\App\\CertificateValidators';

        try{
            $is_valid = true;
            foreach($validators as $validator){

                //Build a fully qualified class name
                $fqn = $ns . '\\' . $validator;

                //Create a dynamic validator
                $dv = new $fqn($cert_parts, $result, $website);
                if(!$dv->is_valid()){
                    $is_valid = false;
                    break;
                }
            }

            $result->setIsValid($is_valid);
        }catch (\Exception $ex){
            throw new \Exception(
                'The TLS validator encountered an unhandled exception',
                0,
                $ex
            );
        }

        $this->entityManager->persist($result);
        $this->entityManager->flush();
    }
}
