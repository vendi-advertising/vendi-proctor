<?php

declare(strict_types=1);

namespace App\Exception\Tls;

class CertMissingDataException extends CertNotValidException
{
    public static function create_missing_key(TranslatorInterface $translator, string $key) : self
    {
        return new self(
            $translator
                ->trans(
                    'The certificate is missing the key %key%',
                    [
                        '%key%' => $key,
                    ]
                )
        );
    }
}
