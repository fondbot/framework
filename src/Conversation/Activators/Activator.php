<?php

declare(strict_types=1);

namespace FondBot\Conversation\Activators;

use FondBot\Drivers\ReceivedMessage;

interface Activator
{
    /**
     * Result of matching activator.
     *
     * @param ReceivedMessage $message
     *
     * @return bool
     */
    public function matches(ReceivedMessage $message): bool;
}
