<?php
declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Objects\Message;

class StoryManager
{

    /**
     * Find story
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
        return $this->findByMessage($message);
    }

    /**
     * Find story by message
     *
     * @param Message $message
     *
     * @return Story|null
     */
    private function findByMessage(Message $message): ?Story
    {
        foreach ($this->stories() as $story) {
            $story = resolve($story);

            /** @var Story $story */
            if (in_array($message->getText(), $story->activations(), true)) {
                return $story;
            }
        }

        return null;
    }

    /**
     * Get stories
     *
     * @return array
     */
    private function stories(): array
    {
        return config('fondbot.stories');
    }

}