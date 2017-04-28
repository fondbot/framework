<?php

declare(strict_types=1);

namespace FondBot\Channels;

use FondBot\Drivers\User;
use FondBot\Drivers\ReceivedMessage;

interface Middleware
{
    /**
     * Handle current user and message.
     *
     * @param User            $user
     * @param ReceivedMessage $message
     */
    public function handle(User $user, ReceivedMessage $message): void;

    /**
     * Determine if user and message matches given requirements.
     *
     * @param User            $user
     * @param ReceivedMessage $message
     *
     * @return bool
     */
    public function matches(User $user, ReceivedMessage $message): bool;
}
