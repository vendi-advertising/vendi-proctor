<?php

declare(strict_types=1);

namespace App\Exception\Tls;

class CertMissingDataException extends CertNotValidException
{
    public static function create_missing_key(string $key) : self
    {
        return new self(
            sprintf(
                'The certificate is missing the key %1$s',
                $key
            )
        );
    }
}
