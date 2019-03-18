<?php

declare(strict_types=1);

namespace App\Entity;

interface TestResultInterface
{
    public function getFailReason(): ?string;

    public function setFailReason(?string $failReason): TestResultInterface;
}