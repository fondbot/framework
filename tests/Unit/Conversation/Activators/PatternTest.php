<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Activators\Pattern;

/**
 * @property mixed|\Mockery\Mock message
 */
class PatternTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->message = $this->mock(ReceivedMessage::class);
    }

    public function test_string_matches(): void
    {
        $this->message->shouldReceive('getText')->andReturn('abc');

        $activator = new Pattern('/abc/');
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function test_string_does_not_match(): void
    {
        $this->message->shouldReceive('getText')->andReturn('ab');

        $activator = new Pattern('/abc/');
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }
}
