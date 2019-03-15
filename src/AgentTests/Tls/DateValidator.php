<?php

declare(strict_types=1);

namespace App\AgentTests\Tls;

use App\AgentTests\AgentTestInterface;
use App\Exception\Tls\CertDateException;
use App\Exception\Tls\CertMissingDataException;

class DateValidator extends CertificateValidatorBase
{
    public function run_test() : string
    {
        $cert_parts = $this->get_cert_parts();
        $result = $this->get_result();

        if (!array_key_exists('validFrom_time_t', $cert_parts)) {
            return $this->add_exception(CertMissingDataException::create_missing_key('validFrom_time_t'));
        }

        if (!array_key_exists('validTo_time_t', $cert_parts)) {
            return $this->add_exception(CertMissingDataException::create_missing_key('validTo_time_t'));
        }

        try {
            $validFrom_time_t = $cert_parts['validFrom_time_t'];
            $validFromDate = new \DateTime("@{$validFrom_time_t}");
            $result->setDateValidFrom($validFromDate);
        } catch (\Exception $ex) {
            return $this->add_exception(CertDateException::create_strange_date_format('validFrom_time_t', $validFrom_time_t));
        }

        try {
            $validTo_time_t = $cert_parts['validTo_time_t'];
            $validToDate = new \DateTime("@{$validTo_time_t}");
            $result->setDateValidTo($validToDate);
        } catch (\Exception $ex) {
            return $this->add_exception(CertDateException::create_strange_date_format('validTo_time_t', $validTo_time_t));
        }

        $now = new \DateTime();

        if ($now < $validFromDate) {
            return $this->add_exception(CertDateException::create_not_ready_yet($validFromDate));
        }

        if ($now > $validToDate) {
            return $this->add_exception(CertDateException::create_expired($validToDate));
        }

        $future = clone $now;
        $future->add(\date_interval_create_from_date_string('10 days'));
        if ($future > $validToDate) {
            return $this->add_warning(CertDateException::create_expiring_soon($validToDate));
        }

        return AgentTestInterface::STATUS_VALID;
    }
}
