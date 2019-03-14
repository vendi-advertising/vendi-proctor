<?php

declare(strict_types=1);

namespace App\CertificateValidators;

interface CertificateValidatorInterface
{
    public function is_valid() : bool;

    public function get_last_exception() : \Exception;

    public function has_exception() : bool;
}
