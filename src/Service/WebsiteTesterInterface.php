<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\TestResultInterface;
use App\Entity\Website;

interface WebsiteTesterInterface
{
    public function validate_single_site(Website $website) : TestResultInterface;
}
