<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use Illuminate\Support\Str;
use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Activator;

class Exact implements Activator
{
    protected $value;
    protected $caseSensitive;

    public function __construct(string $value, bool $caseSensitive = false)
    {
        $this->value = $value;
        $this->caseSensitive = $caseSensitive;
    }

    public function caseSensitive(bool $caseSensitive): self
    {
        $this->caseSensitive = $caseSensitive;

        return $this;
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

        if (!$this->caseSensitive) {
            $text = Str::lower($text);
            $this->value = Str::lower($this->value);
        }

        return hash_equals($this->value, $text);
    }
}
