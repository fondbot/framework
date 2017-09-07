<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Manager;

class ConversationManager implements Manager
{
    private $intents = [];
    private $fallbackIntent;

    /**
     * Register intent.
     *
     * @param string $class
     */
    public function registerIntent(string $class): void
    {
        $this->intents[] = $class;
    }

    /**
     * Register fallback intent.
     *
     * @param string $class
     */
    public function registerFallbackIntent(string $class): void
    {
        $this->fallbackIntent = $class;
    }

    /**
     * Get all registered intents.
     *
     * @return array
     */
    public function getIntents(): array
    {
        return $this->intents;
    }

    /**
     * Match intent by received message.
     *
     * @param MessageReceived $messageReceived
     *
     * @return Intent|null
     */
    public function matchIntent(MessageReceived $messageReceived): ?Intent
    {
        foreach ($this->intents as $intent) {
            /** @var Intent $intent */
            $intent = resolve($intent);

            foreach ($intent->activators() as $activator) {
                if ($activator->matches($messageReceived) && $intent->passesAuthorization($messageReceived)) {
                    return $intent;
                }
            }
        }

        // Otherwise, return fallback intent
        return resolve($this->fallbackIntent);
    }
}
