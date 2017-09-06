<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Events\MessageReceived;

class IntentManager
{
    /** @var Intent[] */
    private $intents = [];

    /** @var Intent */
    private $fallbackIntent;

    /**
     * Register intents.
     *
     * @param array  $intents
     * @param string $fallbackIntent
     */
    public function register(array $intents, string $fallbackIntent): void
    {
        $this->intents = $intents;
        $this->fallbackIntent = $fallbackIntent;
    }

    /**
     * Find intent.
     *
     * @param MessageReceived $message
     *
     * @return Intent|null
     */
    public function find(MessageReceived $message): ?Intent
    {
        foreach ($this->intents as $intent) {
            foreach ($intent->activators() as $activator) {
                if ($activator->matches($message) && $intent->passesAuthorization($message)) {
                    return resolve($intent);
                }
            }
        }

        // Otherwise, return fallback intent
        return resolve($this->fallbackIntent);
    }
}
