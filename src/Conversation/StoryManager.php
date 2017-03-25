<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Conversation\Fallback\FallbackStory;

class StoryManager
{
    private $stories;
    private $fallbackStory;

    public function __construct(array $stories = [], string $fallbackStory = FallbackStory::class)
    {
        $this->stories = $stories;
        $this->fallbackStory = $fallbackStory;
    }

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

        // Find Story by message
        $story = $this->findByMessage($message);

        if ($story !== null) {
            return $story;
        }

        // Otherwise, run fallback story
        return resolve($this->fallbackStory);
    }

    /**
     * Find story by message.
     *
     * @param ReceivedMessage $message
     *
     * @return Story|null
     */
    private function findByMessage(ReceivedMessage $message): ?Story
    {
        foreach ($this->stories as $story) {
            $story = resolve($story);

            /** @var Story $story */
            if (in_array($message->getText(), $story->activations(), true)) {
                return $story;
            }
        }

        return null;
    }

    /**
     * Add story.
     *
     * @param string $story
     */
    public function add(string $story): void
    {
        if (!in_array($story, $this->stories, true)) {
            $this->stories[] = $story;
        }
    }

    /**
     * Set fallback story.
     *
     * @param string $story
     */
    public function setFallbackStory(string $story): void
    {
        $this->fallbackStory = $story;
    }
}
