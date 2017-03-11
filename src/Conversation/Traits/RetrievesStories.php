<?php declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Channels\Objects\Message;
use FondBot\Conversation\Context;
use FondBot\Conversation\Story;

trait RetrievesStories
{

    /** @var Context */
    protected $context;

    /** @var array */
    protected $stories;

    /**
     * Retrieve story
     *
     * @param Message $message
     * @return Story|null
     */
    protected function retrieveStory(Message $message): ?Story
    {
        $story = $this->context->getStory();

        // Context has story
        if ($story !== null) {
            return $story;
        }

        // Find Story by message
        return $this->retrieveStoryByMessage($message);
    }

    /**
     * Retrieve by message
     *
     * @param Message $message
     * @return Story|null
     */
    protected function retrieveStoryByMessage(Message $message): ?Story
    {
        foreach ($this->stories as $story) {
            $story = app($story);

            /** @var Story $story */
            if (in_array($message->getText(), $story->activations(), true)) {
                return $story;
            }
        }

        return null;
    }

}