<?php

declare(strict_types = 1);

namespace App\AgentTests;

abstract class AgentTestBase implements AgentTestInterface
{
    private $status;

    final public function get_status(): string
    {
        return $this->status;
    }

    final public function set_status(string $status)
    {
        $this->status = $status;
    }
}
