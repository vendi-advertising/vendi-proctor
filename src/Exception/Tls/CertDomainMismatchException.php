<?php

declare(strict_types = 1);

namespace App\Exception\Tls;

class CertDomainMismatchException extends CertNotValidException
{
    public static function create(TranslatorInterface $translator) : self
    {
        return new self($translator->trans('The supplied domain is not valid for this certificate'));
    }
}
