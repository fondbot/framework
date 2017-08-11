<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use InvalidArgumentException;
use Illuminate\Container\Container;
use FondBot\Conversation\Interaction;

trait Transitions
{
    /**
     * Jump to another interaction.
     *
     * @param string $interaction
     *
     * @throws \InvalidArgumentException
     */
    protected function jump(string $interaction): void
    {
        /** @var Interaction $instance */
        $instance = Container::getInstance()->make($interaction);

        if (!$instance instanceof Interaction) {
            throw new InvalidArgumentException('Invalid interaction `'.$interaction.'`');
        }

        // TODO
    }

    /**
     * Restart current intent or interaction.
     */
    protected function restart(): void
    {
        // TODO
    }
}
