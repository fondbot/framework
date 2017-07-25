<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Drivers\ReceivedMessage;

class ConversationManager
{
    /**
     * Determine if conversation transitioned.
     *
     * @var bool
     */
    private $transitioned = false;

    /**
     * Handle received message.
     *
     * @param ReceivedMessage $message
     */
    public function handle(ReceivedMessage $message): void
    {
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
            kernel()->closeSession();
            kernel()->clearContext();
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
            $session = kernel()->getSession();
            $session->setIntent($conversable);
            $session->setInteraction(null);

            kernel()->setSession($session);

            $conversable->handle();
        } elseif ($conversable instanceof Interaction) {
            $conversable->handle();
        } else {
            $conversable->handle();
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
                $session = kernel()->getSession();
                $session->setInteraction(null);

                kernel()->setSession($session);

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
        $intentManager = kernel()->resolve(IntentManager::class);

        return $intentManager->find($message);
    }

    /**
     * Determine if conversation started.
     *
     * @return bool
     */
    private function isInConversation(): bool
    {
        return kernel()->getSession()->getInteraction() !== null;
    }
}
