<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Conversation\Intent;
use FondBot\Contracts\Drivers\ReceivedMessage;

class IntentManager
{
    /** @var Intent[] */
    private $intents = [];

    /** @var Intent */
    private $fallbackIntent;

    /**
     * Find intent.
     *
     * @param ReceivedMessage $message
     *
     * @return Intent|null
     */
    public function find(ReceivedMessage $message): ?Intent
    {
        foreach ($this->intents as $intent) {
            foreach ($intent->activators() as $activator) {
                if ($activator->matches($message) && $intent->passesAuthorization()) {
                    return $intent;
                }
            }
        }

        // Otherwise, return fallback intent
        return $this->fallbackIntent;
    }

    /**
     * Add intent.
     *
     * @param Intent $intent
     */
    public function add(Intent $intent): void
    {
        if (!in_array($intent, $this->intents, true)) {
            $this->intents[] = $intent;
        }
    }

    /**
     * Set fallback intent.
     *
     * @param Intent $intent
     */
    public function setFallbackIntent(Intent $intent): void
    {
        $this->fallbackIntent = $intent;
    }
}
