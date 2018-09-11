<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Contracts\Activator;
use Illuminate\Support\Collection;
use FondBot\Events\MessageReceived;

class Contains implements Activator
{
    protected $needles;

    /**
     * @param array|string $needles
     */
    protected function __construct($needles)
    {
        if ($needles instanceof Collection) {
            $needles = $needles->toArray();
        }

        $this->needles = $needles;
    }

    /**
     * @param array|string $needles
     *
     * @return static
     */
    public static function make($needles)
    {
        return new static($needles);
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
