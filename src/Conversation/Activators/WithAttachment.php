<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Events\MessageReceived;

class WithAttachment implements Activator
{
    private $type;

    public function __construct(string $type = null)
    {
        $this->type = $type;
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
        if ($this->type === null) {
            return $message->getAttachment() !== null;
        }

        return hash_equals($message->getAttachment()->getType(), $this->type);
    }
}
