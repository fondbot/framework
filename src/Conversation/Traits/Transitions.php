<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Bot;
use InvalidArgumentException;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Interaction;

trait Transitions
{
    /** @var Bot */
    protected $bot;

    /**
     * Whether any transition run.
     *
     * @var bool
     */
    protected $transitioned = false;

    /**
     * Move to another intent.
     *
     * @param string $intent
     */
    protected function move(string $intent): void
    {
        /** @var Intent $instance */
        $instance = $this->bot->get($intent);

        if (!$instance instanceof Intent) {
            throw new InvalidArgumentException('Invalid intent `'.$intent.'`');
        }

        $this->bot->converse($instance);

        $this->transitioned = true;
    }

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
        $instance = $this->bot->get($interaction);

        if (!$instance instanceof Interaction) {
            throw new InvalidArgumentException('Invalid interaction `'.$interaction.'`');
        }

        // Run interaction
        $this->bot->converse($instance);

        $this->transitioned = true;
    }
}
