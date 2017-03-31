<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Conversation\Activator;

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
     * @param ReceivedMessage $message
     *
     * @return bool
     */
    public function matches(ReceivedMessage $message): bool
    {
        if ($this->type === null) {
            return $message->hasAttachment();
        }

        return $message->hasAttachment() && hash_equals($message->getAttachment()->getType(), $this->type);
    }
}