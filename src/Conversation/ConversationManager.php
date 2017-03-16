<?php

declare(strict_types=1);

namespace FondBot\Conversation;

class ConversationManager
{
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
        $context->setStory($story);
        $this->contextManager->save($context);

        $story->run($context);
    }
}
