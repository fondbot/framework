<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Events\MessageReceived;

class WithPayload implements Activator
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Result of matching activator.
     *
     * @param MessageReceived $message
     *
     * @return bool
     */
    public function matches(MessageReceived $message): bool
    {
        return hash_equals($this->value, $message->getData());
    }
}
