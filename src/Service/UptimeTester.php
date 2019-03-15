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

    public function validate_single_site()
    {
        $res = $this
                ->client
                ->request(
                    'GET', 'https://api.github.com/user', [
            'auth' => ['user', 'pass']
]);
    }
}
