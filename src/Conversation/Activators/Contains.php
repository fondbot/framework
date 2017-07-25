<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Drivers\ReceivedMessage;

class Contains implements Activator
{
    private $needles;

    /**
     * @param array|string $needles
     */
    public function __construct($needles)
    {
        $this->needles = $needles;
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

        return str_contains($text, (array) $this->needles);
    }
}
