<?php

declare(strict_types=1);

namespace App\CertificateValidators;

interface CertificateValidatorInterface
{
    public const STATUS_VALID = 'valid';

    public const STATUS_ERROR = 'error';

    public const STATUS_WARNING = 'warning';

    public const STATUS_UNKNOWN = 'unknown';

    public function get_status() : string;

    public function set_status(string $status);

    public function run_test() : string;

    public function get_last_exception() : \Exception;

    public function has_exception() : bool;
}
