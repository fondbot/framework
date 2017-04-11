<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use InvalidArgumentException;
use FondBot\Application\Kernel;
use FondBot\Conversation\Interaction;

trait Transitions
{
    /** @var Kernel */
    protected $kernel;

    /**
     * Whether any transition run.
     *
     * @var bool
     */
    protected $transitioned = false;

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
        $instance = $this->kernel->get($interaction);

        if (!$instance instanceof Interaction) {
            throw new InvalidArgumentException('Invalid interaction `'.$interaction.'`');
        }

        // Run interaction
        $this->kernel->converse($instance);

        $this->transitioned = true;
    }
}
