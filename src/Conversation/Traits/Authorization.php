<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Drivers\ReceivedMessage;

trait Authorization
{
    /**
     * Determine if intent passes the authorization check.
     *
     * @param ReceivedMessage $message
     *
     * @return bool
     */
    public function passesAuthorization(ReceivedMessage $message): bool
    {
        if (method_exists($this, 'authorize')) {
            return $this->authorize($message);
        }

        return true;
    }
}
