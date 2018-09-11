<?php

declare(strict_types=1);

namespace FondBot\Foundation\Listeners;

use FondBot\FondBot;
use FondBot\Conversation\Context;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\ConversationManager;
use Illuminate\Foundation\Bus\DispatchesJobs;

class HandleConversation
{
    use DispatchesJobs;

    private $kernel;
    private $conversationManager;

    public function __construct(FondBot $kernel, ConversationManager $conversationManager)
    {
        $this->kernel = $kernel;
        $this->conversationManager = $conversationManager;
    }

    public function handle(MessageReceived $messageReceived): void
    {
        /** @var Context $context */
        $context = $this->conversationManager->resolveContext(
            $this->kernel->getChannel(),
            $messageReceived->getChat(),
            $messageReceived->getFrom()
        );

        // If there is no interaction in session
        // Try to match intent and run it
        // Otherwise, run interaction
        if (!$this->isInConversation($context)) {
            $conversable = $this->conversationManager->matchIntent($messageReceived);
        } else {
            $conversable = $context->getInteraction();
        }

        $this->conversationManager->setReceivedMessage($messageReceived);
        $this->conversationManager->converse($conversable);
    }

    /**
     * Determine if conversation started.
     *
     * @param Context $context
     *
     * @return bool
     */
    private function isInConversation(Context $context): bool
    {
        return $context->getInteraction() !== null;
    }
}
