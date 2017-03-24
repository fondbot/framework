<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Traits\Loggable;
use FondBot\Contracts\Channels\Driver;

class ConversationManager
{
    use Loggable;

    private $contextManager;

    public function __construct(ContextManager $contextManager)
    {
        $this->contextManager = $contextManager;
    }

    /**
     * Start or continue conversation.
     *
     * @param Driver  $driver
     * @param Context $context
     * @param Story   $story
     */
    public function start(Driver $driver, Context $context, Story $story): void
    {
        $this->debug('start', ['context' => $context, 'story' => get_class($story)]);

        $context->setStory($story);
        $this->contextManager->save($context);

        $story->setDriver($driver);
        $story->setContext($context);
        $story->run();
    }
}
