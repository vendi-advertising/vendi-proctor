<?php

declare (strict_types = 1);

namespace App\Service;

use App\Entity\Website;
use App\Entity\TestResultInterface;

interface WebsiteTesterInterface
{
    public function validate_single_site(Website $website) : TestResultInterface;
}
