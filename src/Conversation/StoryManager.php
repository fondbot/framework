<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Conversation\Fallback\FallbackStory;

class StoryManager
{
    private $stories;

    public function __construct(array $stories = [])
    {
        $this->stories = $stories;
    }

    /**
     * Find story.
     *
     * @param Context $context
     * @param SenderMessage $message
     *
     * @return Story|null
     */
    public function find(Context $context, SenderMessage $message): ?Story
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
        return resolve(
            config('fondbot.fallback_story', FallbackStory::class)
        );
    }

    /**
     * Find story by message.
     *
     * @param SenderMessage $message
     *
     * @return Story|null
     */
    private function findByMessage(SenderMessage $message): ?Story
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
}
