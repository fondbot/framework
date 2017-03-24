<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Contracts\Channels\Driver;
use InvalidArgumentException;
use FondBot\Conversation\Story;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\ConversationManager;

trait Transitions
{
    use InteractsWithContext;

    /** @var Driver */
    protected $driver;

    /**
     * Whether any transition run.
     *
     * @var bool
     */
    protected $transitioned = false;

    public function getDriver(): Driver
    {
        return $this->driver;
    }

    public function setDriver(Driver $driver): void
    {
        $this->driver = $driver;
    }

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
        $conversationManager->start($this->driver, $this->context, $instance);

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
        $instance = resolve($interaction);

        if (!$instance instanceof Interaction) {
            throw new InvalidArgumentException($interaction.' is not a valid "Interaction".');
        }

        // Run interaction
        $instance->setContext($this->context);
        $instance->setDriver($this->driver);
        $instance->run();

        $this->transitioned = true;
    }
}
