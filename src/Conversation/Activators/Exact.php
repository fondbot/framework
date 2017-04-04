<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Drivers\ReceivedMessage;

class Exact implements Activator
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
        $text = $message->getText();
        if ($text === null) {
            return false;
        }

        return hash_equals($this->value, $text);
    }
}
