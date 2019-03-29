<?php

declare(strict_types=1);

namespace App\Exception\Tls;

use Symfony\Contracts\Translation\TranslatorInterface;

class CertDateException extends CertNotValidException
{
    public static function create_not_ready_yet(TranslatorInterface $translator, \DateTime $cert_date) : self
    {
        return new self(
            $translator
                ->trans(
                    'The supplied cert will not be valid until %date%',
                    [
                        '%date%' => $cert_date->format(\DateTimeInterface::RFC3339_EXTENDED),
                    ]
                )
        );
    }

    public static function create_expired(TranslatorInterface $translator, \DateTime $cert_date) : self
    {
        return new self(
            $translator
                ->trans(
                    'The supplied cert expired at %date%',
                    [
                        '%date%' => $cert_date->format(\DateTimeInterface::RFC3339_EXTENDED),
                    ]
                )
        );
    }

    public static function create_strange_date_format(TranslatorInterface $translator, string $key, string $date_time_string = null) : self
    {
        return new self(
            $translator
                ->trans(
                    'The cert key %key% contained the value %value% which could not be turned into a DateTime object',
                    [
                        '%key%' => $key,
                        '%value%' => is_null($date_time_string) ? 'null' : $date_time_string,
                    ]
                )
        );
    }

    public static function create_expiring_soon(TranslatorInterface $translator, \DateTime $cert_date): self
    {
        return new self(
            $translator
                ->trans(
                    'The supplied cert will be expiring in %day_count% days on %date%',
                    [
                        '%day_count%' => $cert_date->diff(new \DateTime())->format('%a'),
                        '%date%' => $cert_date->format(\DateTimeInterface::RFC3339_EXTENDED),
                    ]
                )
        );
    }
}
