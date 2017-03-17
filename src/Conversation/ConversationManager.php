<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Traits\Loggable;

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
     * @param Context $context
     * @param Story $story
     */
    public function start(Context $context, Story $story): void
    {
        $this->debug('start', ['context' => $context, 'story' => get_class($story)]);

        $context->setStory($story);
        $this->contextManager->save($context);

        $story->setContext($context);
        $story->run();
    }
}
