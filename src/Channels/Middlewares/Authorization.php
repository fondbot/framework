<?php

declare(strict_types=1);

namespace FondBot\Channels\Middlewares;

use FondBot\Drivers\User;
use FondBot\Channels\Middleware;
use FondBot\Drivers\ReceivedMessage;

class Authorization implements Middleware
{
    protected $allowedUsers = [];

    public function __construct(array $allowedUsers = [])
    {
        $this->allowedUsers = $allowedUsers;
    }

    /**
     * Handle current user and message.
     *
     * @param User            $user
     * @param ReceivedMessage $message
     */
    public function handle(User $user, ReceivedMessage $message): void
    {
    }

    /**
     * Determine if user and message matches given requirements.
     *
     * @param User            $user
     * @param ReceivedMessage $message
     *
     * @return bool
     */
    public function matches(User $user, ReceivedMessage $message): bool
    {
        return in_array($user->getId(), $this->allowedUsers, false);
    }
}
