<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Conversation\Story;

class StoryManager
{
    /** @var Story[] */
    private $stories = [];

    /** @var Story */
    private $fallbackStory;

    /**
     * Find story.
     *
     * @param Context         $context
     * @param ReceivedMessage $message
     *
     * @return Story|null
     */
    public function find(Context $context, ReceivedMessage $message): ?Story
    {
        $story = $context->getStory();

        // Context has story
        if ($story !== null) {
            return $story;
        }

        // Find story by activator
        $story = $this->findActivator($message);

        if ($story !== null) {
            return $story;
        }

        // Otherwise, return fallback story
        return $this->fallbackStory;
    }

    /**
     * Find story by message.
     *
     * @param ReceivedMessage $message
     *
     * @return Story|null
     */
    private function findActivator(ReceivedMessage $message): ?Story
    {
        foreach ($this->stories as $story) {
            foreach ($story->activators() as $activator) {
                if ($activator->matches($message) && $story->passesAuthorization()) {
                    return $story;
                }
            }
        }

        return null;
    }

    /**
     * Add story.
     *
     * @param Story $story
     */
    public function add(Story $story): void
    {
        if (!in_array($story, $this->stories, true)) {
            $this->stories[] = $story;
        }
    }

    /**
     * Set fallback story.
     *
     * @param Story $story
     */
    public function setFallbackStory(Story $story): void
    {
        $this->fallbackStory = $story;
    }
}
