<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;

trait InteractsWithSession
{
    /**
     * Get chat.
     *
     * @return Chat
     */
    protected function getChat(): Chat
    {
        return session()->getChat();
    }

    /**
     * Get user.
     *
     * @return User
     */
    protected function getUser(): User
    {
        return session()->getUser();
    }
}
