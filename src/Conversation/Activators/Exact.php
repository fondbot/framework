<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use Illuminate\Support\Str;
use FondBot\Drivers\ReceivedMessage;

class Exact implements Activator
{
    private $value;
    private $caseSensitive;

    public function __construct(string $value, bool $caseSensitive = false)
    {
        $this->value = $value;
        $this->caseSensitive = $caseSensitive;
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

        if (!$this->caseSensitive) {
            $text = Str::lower($text);
        }

        return hash_equals($this->value, $text);
    }
}
