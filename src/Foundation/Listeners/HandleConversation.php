<?php

declare(strict_types=1);

namespace FondBot\Foundation\Listeners;

use FondBot\Foundation\Kernel;
use FondBot\Conversation\Context;
use FondBot\Events\MessageReceived;
use FondBot\Foundation\Commands\Converse;
use FondBot\Contracts\Conversation\Manager;
use Illuminate\Foundation\Bus\DispatchesJobs;

class HandleConversation
{
    use DispatchesJobs;

    private $kernel;
    private $conversation;

    public function __construct(Kernel $kernel, Manager $conversation)
    {
        $this->kernel = $kernel;
        $this->conversation = $conversation;
    }

    public function handle(MessageReceived $message): void
    {
        /** @var Context $context */
        $context = $this->conversation->resolveContext(
            $this->kernel->getChannel(),
            $message->getChat(),
            $message->getFrom()
        );

        // If there is no interaction in session
        // Try to match intent and run it
        // Otherwise, run interaction
        if (!$this->isInConversation($context)) {
            dispatch_now(
                new Converse(
                    $this->conversation->matchIntent($message),
                    $message
                )
            );
        } else {
            dispatch_now(
                new Converse(
                    $context->getInteraction(),
                    $message
                )
            );
        }
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
