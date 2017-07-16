<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Foundation\Kernel;

trait InteractsWithSession
{
    /** @var Kernel */
    protected $kernel;

    /**
     * Get chat.
     *
     * @return Chat
     */
    protected function getChat(): Chat
    {
        return $this->kernel->getSession()->getChat();
    }

    /**
     * Get user.
     *
     * @return User
     */
    protected function getUser(): User
    {
        return $this->kernel->getSession()->getUser();
    }
}
