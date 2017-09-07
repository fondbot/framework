<?php

declare(strict_types=1);

namespace FondBot\Foundation\Listeners;

use FondBot\Foundation\Kernel;
use FondBot\Conversation\Session;
use FondBot\Events\MessageReceived;
use FondBot\Foundation\Commands\Converse;
use FondBot\Contracts\Conversation\Manager;
use FondBot\Foundation\Commands\LoadSession;
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
        /** @var Session $session */
        $session = $this->dispatch(new LoadSession($this->kernel->getChannel(), $message->getChat(), $message->getFrom()));

        $this->kernel->setSession($session);

        // If there is no interaction in session
        // Try to match intent and run it
        // Otherwise, run interaction
        if (!$this->isInConversation($session)) {
            dispatch(
                new Converse(
                    $this->conversation->matchIntent($message),
                    $message
                )
            );
        } else {
            dispatch(
                new Converse(
                    $session->getInteraction(),
                    $message
                )
            );
        }
    }

    /**
     * Determine if conversation started.
     *
     * @param Session $session
     *
     * @return bool
     */
    private function isInConversation(Session $session): bool
    {
        return $session->getInteraction() !== null;
    }
}
