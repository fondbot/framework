<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Http\Request;
use FondBot\Drivers\DriverManager;
use FondBot\Channels\ChannelManager;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Extensions\WebhookVerification;

class ConversationManager
{
    /**
     * Determine if conversation transitioned.
     *
     * @var bool
     */
    private $transitioned = false;

    /**
     * Handle incoming request (webhook).
     *
     * @param string  $channelName
     * @param Request $request
     *
     * @return mixed
     */
    public function handle(string $channelName, Request $request)
    {
        try {
            /** @var ChannelManager $channelManager */
            $channelManager = resolve(ChannelManager::class);

            /** @var DriverManager $driverManager */
            $driverManager = resolve(DriverManager::class);

            $channel = $channelManager->create($channelName);
            $driver = $driverManager->get($channel, $request);

            kernel()->setChannel($channel);
            kernel()->setDriver($driver);

            // Driver has webhook verification
            if ($driver instanceof WebhookVerification && $driver->isVerificationRequest()) {
                return $driver->verifyWebhook();
            }

            // Verify request
            $driver->verifyRequest();

            // Boot kernel
            kernel()->boot($channel, $driver);

            if (!$this->isInConversation()) {
                $this->converse(
                    $this->findIntent()
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
        } catch (InvalidRequest $exception) {
            logger()->warning('ConversationManager[handle] - Invalid Request', ['message' => $exception->getMessage()]);
        }

        return '';
    }

    /**
     * Start conversation.
     *
     * @param Conversable|mixed $conversable
     */
    public function converse(Conversable $conversable): void
    {
        if ($conversable instanceof Intent) {
            $session = session();
            $session->setIntent($conversable);
            $session->setInteraction(null);

            kernel()->setSession($session);

            $conversable->handle(kernel());
        } elseif ($conversable instanceof Interaction) {
            $conversable->handle(kernel());
        } else {
            $conversable->handle(kernel());
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
                $session = session();
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
     * @return Intent|null
     */
    private function findIntent(): Intent
    {
        /** @var IntentManager $intentManager */
        $intentManager = resolve(IntentManager::class);

        return $intentManager->find(kernel()->getDriver()->getMessage());
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
