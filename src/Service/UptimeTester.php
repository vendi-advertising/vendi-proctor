<?php

declare (strict_types = 1);

namespace App\Service;

use App\Entity\TlsScanResult;
use App\Entity\Website;
use App\Exception\Tls\CertMissingDataException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

class UptimeTester
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function validate_single_site(Website $website)
    {
        $time_start = microtime(true);

        $url_parts = [
            'scheme' => 'https',
            'host' => $website->getDomain(),
            'path' => '/'
        ];

        if($website->getPort()){
            $url_parts['port'] = $website->getPort();
        }

        $url = \http_build_url([], $url_parts);

        dd($url);

        try {
            $res = $this
                ->client
                ->request(
                    'GET',
                    $url
                );
        } catch (\Exception $ex) {

        }

        $time_end = microtime(true);
        $execution_time = $time_end - $time_start;

    }
}
