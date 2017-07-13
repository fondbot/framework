<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Activators\WithPayload;

class WithPayloadTest extends TestCase
{
    public function testMatches(): void
    {
        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('hasData')->andReturn(true)->once();
        $message->shouldReceive('getData')->andReturn('foo')->once();

        $activator = new WithPayload('foo');

        $this->assertTrue($activator->matches($message));
    }

    public function testDoesNotMatch(): void
    {
        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('hasData')->andReturn(true)->once();
        $message->shouldReceive('getData')->andReturn('foo')->once();

        $activator = new WithPayload('bar');

        $this->assertFalse($activator->matches($message));
    }
}
