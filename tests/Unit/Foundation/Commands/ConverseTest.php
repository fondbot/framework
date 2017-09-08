<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Context;
use FondBot\Events\MessageReceived;
use FondBot\Foundation\Commands\Converse;

class ConverseTest extends TestCase
{
    public function testIntent(): void
    {
        $context = new Context(
            $this->mock(Channel::class),
            $this->mock(Chat::class),
            $this->mock(User::class)
        );
        $intent = $this->mock(Intent::class);
        $messageReceived = $this->mock(MessageReceived::class);

        $this->setContext($context);
        $intent->shouldReceive('handle')->with($messageReceived)->once();

        Converse::dispatch($intent, $messageReceived);

        $this->assertSame($intent, $context->getIntent());
        $this->assertNull($context->getInteraction());
    }
}
