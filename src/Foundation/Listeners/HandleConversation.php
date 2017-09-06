<?php

declare(strict_types=1);

namespace FondBot\Foundation\Listeners;

use FondBot\Foundation\Kernel;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Session;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\IntentManager;
use FondBot\Foundation\Commands\Converse;
use FondBot\Foundation\Commands\LoadSession;
use Illuminate\Foundation\Bus\DispatchesJobs;

class HandleConversation
{
    use DispatchesJobs;

    private $kernel;
    private $intentManager;

    public function __construct(Kernel $kernel, IntentManager $intentManager)
    {
        $this->kernel = $kernel;
        $this->intentManager = $intentManager;
    }

    public function handle(MessageReceived $message): void
    {
        /** @var Session $session */
        $session = $this->dispatch(new LoadSession($this->kernel->getChannel(), $message->getChat(), $message->getFrom()));

        $this->kernel->setSession($session);

        // If there is no interaction in session
        // Try to find intent and run it
        // Otherwise, run interaction
        if (!$this->isInConversation($session)) {
            dispatch(
                new Converse(
                    $this->findIntent($message),
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
     * Find matching intent.
     *
     * @param MessageReceived $event
     *
     * @return Intent|null
     */
    private function findIntent(MessageReceived $event): Intent
    {
        return $this->intentManager->find($event);
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
