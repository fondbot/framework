<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Bot;
use InvalidArgumentException;
use FondBot\Conversation\Story;
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
     * Move to another story.
     *
     * @param string $story
     */
    protected function move(string $story): void
    {
        /** @var Story $instance */
        $instance = $this->bot->get($story);

        if (!$instance instanceof Story) {
            throw new InvalidArgumentException('Invalid story `'.$story.'`');
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
        $instance->handle($this->bot);

        $this->transitioned = true;
    }
}
