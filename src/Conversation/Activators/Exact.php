<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use Illuminate\Support\Str;
use FondBot\Contracts\Activator;
use FondBot\Events\MessageReceived;

class Exact implements Activator
{
    protected $value;
    protected $caseSensitive;

    protected function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function make(string $value)
    {
        return new static($value);
    }

    public function caseSensitive(): self
    {
        $this->caseSensitive = true;

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
