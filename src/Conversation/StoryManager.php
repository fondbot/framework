<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Channels\Message;
use FondBot\Conversation\Fallback\FallbackStory;

class StoryManager
{
    /**
     * Find story.
     *
     * @param Context $context
     * @param Message $message
     *
     * @return Story|null
     */
    public function find(Context $context, Message $message): ?Story
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
     * @param Message $message
     *
     * @return Story|null
     */
    private function findByMessage(Message $message): ?Story
    {
        foreach ($this->getStories() as $story) {
            $story = resolve($story);

            /** @var Story $story */
            if (in_array($message->getText(), $story->activations(), true)) {
                return $story;
            }
        }

        return null;
    }

    /**
     * Get stories.
     *
     * @return array
     */
    private function getStories(): array
    {
        return config('fondbot.stories');
    }
}
