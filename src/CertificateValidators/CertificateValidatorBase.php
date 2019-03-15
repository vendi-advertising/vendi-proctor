<?php

declare(strict_types=1);

namespace App\CertificateValidators;

use App\Entity\TlsScanResult;
use App\Entity\Website;

abstract class CertificateValidatorBase implements CertificateValidatorInterface
{
    private $cert_parts;
    private $result;
    private $website;
    private $exceptions = [];

    public function __construct(array $cert_parts, TlsScanResult $result, Website $website)
    {
        $this->cert_parts = $cert_parts;
        $this->result = $result;
        $this->website = $website;
    }

    final public function get_cert_parts() : array
    {
        return $this->cert_parts;
    }

    final public function get_result() : TlsScanResult
    {
        return $this->result;
    }

    final public function get_website() : Website
    {
        return $this->website;
    }

    final public function get_last_exception() : \Exception
    {
        if ($this->has_exception()) {
            return end($this->exceptions);
        }

        return null;
    }

    final public function add_exception(\Exception $ex)
    {
        $this->exceptions[] = $ex;
    }

    final public function has_exception() : bool
    {
        return count($this->exceptions) > 0;
    }
}
