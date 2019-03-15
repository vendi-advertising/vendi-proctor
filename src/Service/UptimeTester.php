<?php

declare (strict_types = 1);

namespace App\Service;

use App\CertificateValidators\CertificateValidatorInterface;
use App\CertificateValidators\DateValidator;
use App\CertificateValidators\DomainNameValidator;
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
}
