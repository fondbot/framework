<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

use FondBot\Conversation\Intent;
use FondBot\Events\MessageReceived;

interface Manager
{
    /**
     * Register intent.
     *
     * @param string $class
     */
    public function registerIntent(string $class): void;

    /**
     * Register fallback intent.
     *
     * @param string $class
     */
    public function registerFallbackIntent(string $class): void;

    /**
     * Get all registered intents.
     *
     * @return array
     */
    public function getIntents(): array;

    /**
     * Match intent by received message.
     *
     * @param MessageReceived $messageReceived
     *
     * @return Intent|null
     */
    public function matchIntent(MessageReceived $messageReceived): ?Intent;
}
