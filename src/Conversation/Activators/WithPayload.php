<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Drivers\ReceivedMessage;

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
     * @param ReceivedMessage $message
     *
     * @return bool
     */
    public function matches(ReceivedMessage $message): bool
    {
        return $message->hasData() && hash_equals($this->value, $message->getData());
    }
}
