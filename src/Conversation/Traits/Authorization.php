<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

trait Authorization
{
    /**
     * Determine if intent passes the authorization check.
     *
     * @return bool
     */
    public function passesAuthorization(): bool
    {
        return true;
    }
}
