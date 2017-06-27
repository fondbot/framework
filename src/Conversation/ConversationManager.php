<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Foundation\Kernel;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Drivers\Exceptions\InvalidRequest;

class ConversationManager
{
    /**
     * Determine if conversation transitioned.
     *
     * @var bool
     */
    private $transitioned = false;

    private $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Handle received message.
     *
     * @param ReceivedMessage $message
     */
    public function handle(ReceivedMessage $message): void
    {
        try {
            if (!$this->isInConversation()) {
                $this->converse(
                    $this->findIntent($message)
                );
            } else {
                $this->converse(
                    session()->getInteraction()
                );
            }

            // Close session if conversation has not been transitioned
            // Otherwise, save session state
            if (!$this->transitioned) {
                $this->kernel->closeSession();
            } else {
                $this->kernel->saveSession();
            }
        } catch (InvalidRequest $exception) {
            logger()->warning('ConversationManager[handle] - Invalid Request', ['message' => $exception->getMessage()]);
        }
    }

    /**
     * Start conversation.
     *
     * @param Conversable|mixed $conversable
     */
    public function converse(Conversable $conversable): void
    {
        if ($conversable instanceof Intent) {
            $session = $this->kernel->getSession();
            $session->setIntent($conversable);
            $session->setInteraction(null);
            $session->setContext([]);

            $this->kernel->setSession($session);

            $conversable->handle($this->kernel);
        } elseif ($conversable instanceof Interaction) {
            $conversable->handle($this->kernel);
        } else {
            $conversable->handle($this->kernel);
        }
    }

    /**
     * Transition to intent or interaction.
     *
     * @param Conversable $conversable
     */
    public function transition(Conversable $conversable): void
    {
        $this->converse($conversable);
        $this->transitioned = true;
    }

    /**
     * Restart intent or interaction.
     *
     * @param Conversable|mixed $conversable
     */
    public function restart(Conversable $conversable): void
    {
        switch (true) {
            case $conversable instanceof Intent:
                $this->converse($conversable);

                $this->transitioned = true;
                break;
            case $conversable instanceof Interaction:
                $session = $this->kernel->getSession();
                $session->setInteraction(null);
                $session->setContext([]);

                $this->kernel->setSession($session);

                $this->transitioned = true;

                $this->converse($conversable);
                break;
        }
    }

    /**
     * Find matching intent.
     *
     * @param ReceivedMessage $message
     *
     * @return Intent|null
     */
    private function findIntent(ReceivedMessage $message): Intent
    {
        /** @var IntentManager $intentManager */
        $intentManager = $this->kernel->resolve(IntentManager::class);

        return $intentManager->find($message);
    }

    /**
     * Determine if conversation started.
     *
     * @return bool
     */
    private function isInConversation(): bool
    {
        return $this->kernel->getSession()->getInteraction() !== null;
    }
}
