<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Channels\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Foundation\Commands\SendRequest;

class SendRequestTest extends TestCase
{
    public function test(): void
    {
        $chat = $this->mock(Chat::class);
        $recipient = $this->mock(User::class);
        $endpoint = $this->faker()->word;
        $parameters = ['foo' => 'bar'];

        $command = new SendRequest($chat, $recipient, $endpoint, $parameters);
    }
}
