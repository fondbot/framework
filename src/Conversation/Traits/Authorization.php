<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Events\MessageReceived;

trait Authorization
{
    /**
     * Determine if intent passes the authorization check.
     *
     * @param MessageReceived $message
     *
     * @return bool
     */
    public function passesAuthorization(MessageReceived $message): bool
    {
        if (method_exists($this, 'authorize')) {
            return $this->authorize($message);
        }

        return true;
    }
}
