<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Contracts\Activator;
use FondBot\Events\MessageReceived;

class Payload implements Activator
{
    protected $value;

    protected function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function make(string $value)
    {
        return new static($value);
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
        return $message->getData() ? hash_equals($this->value, $message->getData()) : false;
    }
}
