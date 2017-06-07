<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Conversation\Activators\WithPayload;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Tests\TestCase;

class WithPayloadTest extends TestCase
{
    public function test_matches(): void
    {
        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('hasData')->andReturn(true)->once();
        $message->shouldReceive('getData')->andReturn('foo')->once();

        $activator = new WithPayload('foo');

        $this->assertTrue($activator->matches($message));
    }

    public function test_does_not_match(): void
    {
        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('hasData')->andReturn(true)->once();
        $message->shouldReceive('getData')->andReturn('foo')->once();

        $activator = new WithPayload('bar');

        $this->assertFalse($activator->matches($message));
    }
}