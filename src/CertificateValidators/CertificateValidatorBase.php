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
    private $status;

    public function __construct(array $cert_parts, TlsScanResult $result, Website $website)
    {
        $this->cert_parts = $cert_parts;
        $this->result = $result;
        $this->website = $website;
        $this->status = CertificateValidatorInterface::STATUS_UNKNOWN;
    }

    final public function get_status() : string
    {
        return $this->status;
    }

    final public function set_status(string $status)
    {
        $this->status = $status;
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

    final public function add_exception(\Exception $ex) : string
    {
        $this->exceptions[] = $ex;
        return CertificateValidatorInterface::STATUS_ERROR;
    }

    final public function add_warning(\Exception $ex): string
    {
        $this->exceptions[] = $ex;
        return CertificateValidatorInterface::STATUS_WARNING;
    }

    final public function has_exception() : bool
    {
        return count($this->exceptions) > 0;
    }
}
