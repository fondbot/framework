<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Activator;

class Contains implements Activator
{
    protected $needles;

    /**
     * @param array|string $needles
     */
    public function __construct($needles)
    {
        if (is_string($needles)) {
            $needles = str_getcsv($needles);
        }

        $this->needles = $needles;
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
        $text = $message->getText();
        if ($text === null) {
            return false;
        }

        return str_contains($text, (array) $this->needles);
    }
}
