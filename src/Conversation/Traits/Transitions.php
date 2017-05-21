<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use RuntimeException;
use InvalidArgumentException;
use FondBot\Application\Kernel;
use FondBot\Conversation\Intent;
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
        $instance = $this->kernel->resolve($interaction);

        if (!$instance instanceof Interaction) {
            throw new InvalidArgumentException('Invalid interaction `'.$interaction.'`');
        }

        // Run interaction
        $this->kernel->converse($instance);

        $this->transitioned = true;
    }

    /**
     * Restart current intent or interaction.
     */
    protected function restart(): void
    {
        switch (true) {
            case $this instanceof Intent:
                $this->kernel->clearSession();

                $this->kernel->converse($this);

                $this->transitioned = true;
                break;
            case $this instanceof Interaction:
                $session = $this->kernel->getSession();
                $session->setInteraction(null);
                $session->setValues([]);
                $this->kernel->setSession($session);

                $this->transitioned = true;

                $this->kernel->converse($this);
                break;
            default:
                throw new RuntimeException('Only conversable instances can be restarted.');
        }
    }
}
