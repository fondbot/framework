<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Application\Kernel;

trait InteractsWithContext
{
    /** @var Kernel */
    protected $kernel;

    /**
     * Remember value in context.
     *
     * @param string $key
     * @param        $value
     */
    protected function remember(string $key, $value): void
    {
        $this->kernel->getContext()->setValue($key, $value);
    }

    /**
     * Get chat.
     *
     * @return Chat
     */
    protected function getChat(): Chat
    {
        return $this->kernel->getContext()->getChat();
    }

    /**
     * Get user.
     *
     * @return User
     */
    protected function getUser(): User
    {
        return $this->kernel->getContext()->getUser();
    }
}
