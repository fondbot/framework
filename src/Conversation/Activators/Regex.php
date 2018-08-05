<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use Illuminate\Support\Collection;
use FondBot\Events\MessageReceived;
use FondBot\Contracts\Conversation\Activator;

class Regex implements Activator
{
    /** @var array|string */
    protected $patterns;

    protected function __construct($patterns)
    {
        if ($patterns instanceof Collection) {
            $patterns = $patterns->toArray();
        }

        $this->patterns = $patterns;
    }

    public static function make($patterns)
    {
        return new static($patterns);
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
        return str_is($this->patterns, $message->getText());
    }
}
