<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Activators\Contains;

/**
 * @property mixed|\Mockery\Mock message
 */
class ContainsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->message = $this->mock(ReceivedMessage::class);
    }

    public function test_matches(): void
    {
        $this->message->shouldReceive('getText')->andReturn('this is foo');

        $activator = new Contains('foo');
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function test_does_not_match(): void
    {
        $this->message->shouldReceive('getText')->andReturn('this is bar');

        $activator = new Contains('foo');
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }

    public function test_message_does_not_have_text(): void
    {
        $this->message->shouldReceive('getText')->andReturn(null);

        $activator = new Contains('foo');
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }
}
