<?php

declare(strict_types=1);

namespace FondBot\Foundation\Listeners;

use FondBot\FondBot;
use FondBot\Conversation\Context;
use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Manager;
use Illuminate\Foundation\Bus\DispatchesJobs;

class HandleConversation
{
    use DispatchesJobs;

    private $kernel;
    private $conversation;

    public function __construct(FondBot $kernel, Manager $conversation)
    {
        $this->kernel = $kernel;
        $this->conversation = $conversation;
    }

    public function handle(MessageReceived $messageReceived): void
    {
        /** @var Context $context */
        $context = $this->conversation->resolveContext(
            $this->kernel->getChannel(),
            $messageReceived->getChat(),
            $messageReceived->getFrom()
        );

        // If there is no interaction in session
        // Try to match intent and run it
        // Otherwise, run interaction
        if (!$this->isInConversation($context)) {
            $conversable = $this->conversation->matchIntent($messageReceived);
        } else {
            $conversable = $context->getInteraction();
        }

        $this->conversation->setReceivedMessage($messageReceived);
        $this->conversation->converse($conversable);
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
