<?php

declare(strict_types=1);

namespace App\CertificateValidators;

use App\Exception\Tls\CertDateException;
use App\Exception\Tls\CertMissingDataException;

class DateValidator extends CertificateValidatorBase
{
    public function is_valid() : bool
    {
        $cert_parts = $this->get_cert_parts();
        $result = $this->get_result();

        if (!array_key_exists('validFrom_time_t', $cert_parts)) {
            $this->add_exception(CertMissingDataException::create_missing_key('validFrom_time_t'));
            return false;
        }

        if (!array_key_exists('validTo_time_t', $cert_parts)) {
            $this->add_exception(CertMissingDataException::create_missing_key('validTo_time_t'));
            return false;
        }

        try {
            $validFrom_time_t = $cert_parts['validFrom_time_t'];
            $validFromDate = new \DateTime("@{$validFrom_time_t}");
            $result->setDateValidFrom($validFromDate);
        } catch (\Exception $ex) {
            $this->add_exception(CertDateException::create_strange_date_format('validFrom_time_t', $validFrom_time_t));
            return false;
        }

        try {
            $validTo_time_t = $cert_parts['validTo_time_t'];
            $validToDate = new \DateTime("@{$validTo_time_t}");
            $result->setDateValidTo($validToDate);
        } catch (\Exception $ex) {
            $this->add_exception(CertDateException::create_strange_date_format('validTo_time_t', $validTo_time_t));
            return false;
        }

        $now = new \DateTime();

        if ($now < $validFromDate) {
            $this->add_exception(CertDateException::create_not_ready_yet($validFromDate));
            return false;
        }

        if ($now > $validToDate) {
            $this->add_exception(CertDateException::create_expired($validToDate));
            return false;
        }

        //Just in case we ever miss a return above we don't return true here
        return !$this->has_exception();
    }
}
