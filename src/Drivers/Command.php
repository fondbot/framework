<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class Command implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Get name.
     *
     * @return string
     */
    abstract public function getName(): string;
}
