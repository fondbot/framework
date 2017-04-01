<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Conversation\Intent;
use FondBot\Contracts\Channels\ReceivedMessage;

class IntentManager
{
    /** @var Intent[] */
    private $intents = [];

    /** @var Intent */
    private $fallbackIntent;

    /**
     * Find intent.
     *
     * @param Context         $context
     * @param ReceivedMessage $message
     *
     * @return Intent|null
     */
    public function find(Context $context, ReceivedMessage $message): ?Intent
    {
        $intent = $context->getIntent();

        // Context has intent
        if ($intent !== null) {
            return $intent;
        }

        // Find intent by activator
        $intent = $this->findActivator($message);

        if ($intent !== null) {
            return $intent;
        }

        // Otherwise, return fallback intent
        return $this->fallbackIntent;
    }

    /**
     * Find intent by message.
     *
     * @param ReceivedMessage $message
     *
     * @return Intent|null
     */
    private function findActivator(ReceivedMessage $message): ?Intent
    {
        foreach ($this->intents as $intent) {
            foreach ($intent->activators() as $activator) {
                if ($activator->matches($message) && $intent->passesAuthorization()) {
                    return $intent;
                }
            }
        }

        return null;
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
