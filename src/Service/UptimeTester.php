<?php

declare (strict_types = 1);

namespace App\Service;

use App\Entity\TestResultInterface;
use App\Entity\UptimeResult;
use App\Entity\Website;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\TransferException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UptimeTester implements WebsiteTesterInterface
{
    private $container;

    private $entityManager;

    private $caBundleLoader;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager, CABundleLoader $caBundleLoader)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->caBundleLoader = $caBundleLoader;
    }

    public function validate_single_site(Website $website) : TestResultInterface
    {
        $result = new UptimeResult();
        $result->setWebsite($website);

        $ca_bundle_filepath = $this->caBundleLoader->get_most_recent_pem_file();

        /** @var \GuzzleHttp\Client $client */
        $client   = $this->container->get('eight_points_guzzle.client.uptime');

        $url_parts = [
            'scheme' => 'https',
            'host' => $website->getDomain(),
            'path' => '/'
        ];

        if($website->getPort()){
            $url_parts['port'] = $website->getPort();
        }

        $url = \http_build_url([], $url_parts);

        try {

            $time_start = microtime(true);
            $res = $client
                ->request(
                    'GET',
                    $url,
                    [
                        'verify' => $ca_bundle_filepath,
                    ]
                );
            $time_end = microtime(true);
            $execution_time = $time_end - $time_start;

            $result->setLoadTimeInMs($execution_time);
            $result->setHttpStatus($res->getStatusCode());
            $result->set_status_valid();

        } catch(ConnectException $ex) {
            //DNS/Connection problems
            $result->setFailReason($ex->getMessage());
            $result->set_status_error();

        } catch( ClientException $ex ) {
            // HTTP 4xx
            $result->setHttpStatus($ex->getResponse()->getStatusCode());
            $result->setFailReason($ex->getMessage());
            $result->set_status_error();
            
        } catch( ServerException $ex ) {
            //HTTP 5xx
            $result->setHttpStatus($ex->getResponse()->getStatusCode());
            $result->setFailReason($ex->getMessage());
            $result->set_status_error();
            
        } catch( TooManyRedirectsException $ex ){
            //Too many redirects
            $result->setFailReason($ex->getMessage());
            $result->set_status_error();
            
        } catch( TransferException $ex ) {
            //General Guzzle Exception
            $result->setFailReason($ex->getMessage());
            $result->set_status_error();

        // } catch (\Exception $ex) {
        //     $result->setFailReason($ex->getMessage());
        //     $result->set_status_error();
        }
        
        $this->entityManager->persist($result);
        $this->entityManager->flush();

        return $result;

    }
}
