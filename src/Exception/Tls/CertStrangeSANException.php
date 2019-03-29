<?php

declare(strict_types=1);

namespace App\Exception\Tls;

use Symfony\Contracts\Translation\TranslatorInterface;

class CertStrangeSANException extends CertNotValidException
{
    public static function create_strange_format(TranslatorInterface $translator, string $format) : self
    {
        return new self(
            $translator
                ->trans(
                    'SAN item has a strange format: %format%',
                    [
                        '%format%' => $format,
                    ]
                )
        );
    }

    public static function create_strange_type(TranslatorInterface $translator, string $type) : self
    {
        return new self(
            $translator
                ->trans(
                    'SAN item has a strange type: %type%',
                    [
                        '%type%' => $type,
                    ]
                )
        );
    }
}
