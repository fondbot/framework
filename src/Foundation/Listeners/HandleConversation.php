<?php

declare(strict_types=1);

namespace FondBot\Foundation\Listeners;

use FondBot\Conversation\Intent;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\IntentManager;
use Illuminate\Contracts\Bus\Dispatcher;
use FondBot\Foundation\Commands\Converse;
use Illuminate\Contracts\Container\Container;

class HandleConversation
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function handle(MessageReceived $message): void
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        if (!$this->isInConversation()) {
            $dispatcher->dispatch(
                new Converse(
                    $this->findIntent($message),
                    $message
                )
            );
        } else {
            $dispatcher->dispatch(
                new Converse(
                    session()->getInteraction(),
                    $message
                )
            );
        }

        // TODO
        // Close session if conversation has not been transitioned
        // Otherwise, save session state
//        if (!$this->transitioned) {
//            kernel()->closeSession();
//            kernel()->clearContext();
//        }
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
        /** @var IntentManager $intentManager */
        $intentManager = $this->container->make(IntentManager::class);

        return $intentManager->find($event);
    }

    /**
     * Determine if conversation started.
     *
     * @return bool
     */
    private function isInConversation(): bool
    {
        return session()->getInteraction() !== null;
    }
}
