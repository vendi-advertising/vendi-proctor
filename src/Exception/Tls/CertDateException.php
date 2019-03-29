<?php

declare(strict_types=1);

namespace App\Exception\Tls;

class CertDateException extends CertNotValidException
{
    public static function create_not_ready_yet(\DateTime $cert_date) : self
    {
        return new self(
            sprintf(
                'The supplied cert will not be valid until %1$s',
                $cert_date->format(\DateTimeInterface::RFC3339_EXTENDED)
            )
        );
    }

    public static function create_expired(\DateTime $cert_date) : self
    {
        return new self(
            sprintf(
                'The supplied cert expired at %1$s',
                $cert_date->format(\DateTimeInterface::RFC3339_EXTENDED)
            )
        );
    }

    public static function create_strange_date_format(string $key, string $date_time_string = null) : self
    {
        return new self(
            sprintf(
                'The cert key %1$s contained the value %2$s which could not be turned into a DateTime object',
                $key,
                is_null($date_time_string) ? 'null' : $date_time_string
            )
        );
    }

    public static function create_expiring_soon(\DateTime $cert_date): self
    {
        return new self(
            sprintf(
                'The supplied cert will be expiring in %1$d days on %2$s',
                $cert_date->diff(new \DateTime())->format('%a'),
                $cert_date->format(\DateTimeInterface::RFC3339_EXTENDED)
            )
        );
    }
}
