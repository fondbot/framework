<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Queue;
use FondBot\Channels\Channel;
use FondBot\Conversation\Session;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\FallbackIntent;

/**
 * @property FallbackIntent $intent
 */
class FallbackIntentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->intent = new FallbackIntent;
    }

    public function testActivators(): void
    {
        $this->assertSame([], $this->intent->activators());
    }

    public function testRun(): void
    {
        $queue = $this->mock(Queue::class);
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $session = $this->mock(Session::class);
        $message = $this->mock(ReceivedMessage::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);

        $this->kernel->setChannel($channel);
        $this->kernel->setDriver($driver);
        $this->kernel->setSession($session);
        $session->shouldReceive('getMessage')->andReturn($message)->atLeast()->once();
        $session->shouldReceive('getChat')->andReturn($chat)->atLeast()->once();
        $session->shouldReceive('getUser')->andReturn($user)->atLeast()->once();

        $queue->shouldReceive('push')->once();

        $this->intent->handle($this->kernel);
    }
}
