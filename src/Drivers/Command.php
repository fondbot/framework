<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use Illuminate\Contracts\Queue\ShouldQueue;

abstract class Command implements ShouldQueue
{
    /**
     * Get name.
     *
     * @return string
     */
    abstract public function getName(): string;
}
