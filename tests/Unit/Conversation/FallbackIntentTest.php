<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Conversation\Context;
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
        $context = $this->mock(Context::class);
        $message = $this->mock(MessageReceived::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);

        $this->setContext($context);

        $context->shouldReceive('getChat')->andReturn($chat)->atLeast()->once();
        $context->shouldReceive('getUser')->andReturn($user)->atLeast()->once();

        (new FallbackIntent)->handle($message);
    }
}
