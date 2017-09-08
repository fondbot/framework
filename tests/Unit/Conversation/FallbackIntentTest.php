<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Events\MessageReceived;
use Illuminate\Support\Facades\Bus;
use FondBot\Conversation\FallbackIntent;
use FondBot\Foundation\Commands\SendMessage;

class FallbackIntentTest extends TestCase
{
    public function testActivators(): void
    {
        $this->assertSame([], (new FallbackIntent)->activators());
    }

    public function testRun(): void
    {
        Bus::fake();

        $context = $this->mock(Context::class);
        $message = $this->mock(MessageReceived::class);
        $channel = $this->mock(Channel::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);

        $this->setContext($context);

        $context->shouldReceive('getChannel')->andReturn($channel)->atLeast()->once();
        $context->shouldReceive('getChat')->andReturn($chat)->atLeast()->once();
        $context->shouldReceive('getUser')->andReturn($user)->atLeast()->once();

        (new FallbackIntent)->handle($message);

        Bus::assertDispatched(SendMessage::class);
    }
}
