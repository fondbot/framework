<?php

declare(strict_types=1);

namespace FondBot\Tests\Classes;

use Mockery;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Drivers\Command;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Drivers\Exceptions\InvalidRequest;

class FakeDriver extends Driver
{
    /**
     * Verify incoming request data.
     *
     * @throws InvalidRequest
     */
    public function verifyRequest(): void
    {
    }

    /**
     * Get current chat.
     *
     * @return Chat|mixed
     */
    public function getChat(): Chat
    {
        return Mockery::mock(Chat::class);
    }

    /**
     * Get current user.
     *
     * @return User|mixed
     */
    public function getUser(): User
    {
        return Mockery::mock(User::class);
    }

    /**
     * Get message received from sender.
     *
     * @return ReceivedMessage|mixed
     */
    public function getMessage(): ReceivedMessage
    {
        return Mockery::mock(ReceivedMessage::class);
    }

    /**
     * Handle command.
     *
     * @param Command $command
     */
    public function handle(Command $command): void
    {
    }
}
