<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Tests\TestCase;
use FondBot\Conversation\Session;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\FallbackIntent;

class FallbackIntentTest extends TestCase
{
    public function testActivators(): void
    {
        $this->assertSame([], (new FallbackIntent)->activators());
    }

    public function testRun(): void
    {
        $session = $this->mock(Session::class);
        $message = $this->mock(MessageReceived::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);

        $this->kernel->shouldReceive('getSession')->andReturn($session)->atLeast()->once();

        $session->shouldReceive('getChat')->andReturn($chat)->atLeast()->once();
        $session->shouldReceive('getUser')->andReturn($user)->atLeast()->once();

        (new FallbackIntent)->handle($message);
    }
}
