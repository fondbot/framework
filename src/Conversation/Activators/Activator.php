<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Events\MessageReceived;

interface Activator
{
    /**
     * Result of matching activator.
     *
     * @param MessageReceived $message
     *
     * @return bool
     */
    public function matches(MessageReceived $message): bool;
}
