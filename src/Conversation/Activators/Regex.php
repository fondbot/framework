<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Events\MessageReceived;
use Spatie\Regex\Regex as SpatieRegex;
use FondBot\Contracts\Conversation\Activator;

class Regex implements Activator
{
    protected $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
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

        return SpatieRegex::match($this->pattern, $text)->hasMatch();
    }
}
