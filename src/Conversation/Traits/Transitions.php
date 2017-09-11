<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Contracts\Conversation\Manager;

trait Transitions
{
    /**
     * Jump to intent or interaction.
     *
     * @throws \InvalidArgumentException
     */
    public static function jump(): void
    {
        /** @var Manager $conversation */
        $conversation = resolve(Manager::class);
        $conversation->transition(static::class);
    }

    /**
     * Restart current intent or interaction.
     */
    protected function restart(): void
    {
        /** @var Manager $conversation */
        $conversation = resolve(Manager::class);
        $conversation->restart($this);
    }
}
