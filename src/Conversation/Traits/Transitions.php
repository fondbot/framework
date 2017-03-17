<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use InvalidArgumentException;
use FondBot\Conversation\Story;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\ConversationManager;

trait Transitions
{
    use InteractsWithContext;

    /**
     * Move to another story.
     *
     * @param string $story
     */
    protected function move(string $story): void
    {
        /** @var Story $instance */
        $instance = resolve($story);

        if (!$instance instanceof Story) {
            throw new InvalidArgumentException($story.' is not a valid "Story".');
        }

        $this->getContext()->setStory($instance);
        $this->getContext()->setInteraction(null);
        $this->getContext()->setValues([]);
        $this->updateContext();

        /** @var ConversationManager $conversationManager */
        $conversationManager = resolve(ConversationManager::class);
        $conversationManager->start($this->getContext(), $instance);
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
        $instance = resolve($interaction);

        if (!$instance instanceof Interaction) {
            throw new InvalidArgumentException($interaction.' is not a valid "Interaction".');
        }

        // Run interaction
        $instance->setContext($this->context);
        $instance->run();
    }
}
