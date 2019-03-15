<?php

declare (strict_types = 1);

namespace App\Exception\Tls;

class CertDomainMismatchException extends CertNotValidException
{
    public static function create() : self
    {
        return new self('The supplied domain is not valid for this certificate');
    }
}
