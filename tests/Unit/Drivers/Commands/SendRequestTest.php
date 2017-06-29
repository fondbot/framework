<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Commands\SendRequest;

class SendRequestTest extends TestCase
{
    public function test(): void
    {
        $chat = $this->mock(Chat::class);
        $recipient = $this->mock(User::class);
        $endpoint = $this->faker()->word;
        $parameters = ['foo' => 'bar'];

        $command = new SendRequest($chat, $recipient, $endpoint, $parameters);

        $this->assertSame('SendRequest', $command->getName());
        $this->assertSame($chat, $command->getChat());
        $this->assertSame($recipient, $command->getRecipient());
        $this->assertSame($endpoint, $command->getEndpoint());
        $this->assertSame($parameters, $command->getParameters());
    }
}
