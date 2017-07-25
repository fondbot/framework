<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use InvalidArgumentException;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\ConversationManager;

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
        $instance = kernel()->resolve($interaction);

        if (!$instance instanceof Interaction) {
            throw new InvalidArgumentException('Invalid interaction `'.$interaction.'`');
        }

        $this->conversationManager()->transition($instance);
    }

    /**
     * Restart current intent or interaction.
     */
    protected function restart(): void
    {
        $this->conversationManager()->restart($this);
    }

    private function conversationManager(): ConversationManager
    {
        return kernel()->resolve(ConversationManager::class);
    }
}
