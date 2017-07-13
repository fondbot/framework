<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Activators\Exact;

/**
 * @property mixed|\Mockery\Mock message
 */
class ExactTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->message = $this->mock(ReceivedMessage::class);
    }

    public function testMatchesCaseSensitive(): void
    {
        $this->message->shouldReceive('getText')->andReturn('/start');

        $activator = new Exact('/start', true);
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function testDoesNotMatchCaseSensitive(): void
    {
        $this->message->shouldReceive('getText')->andReturn('/Start');

        $activator = new Exact('/start', true);
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }

    public function testMatchesCaseInsensitive(): void
    {
        $this->message->shouldReceive('getText')->andReturn('/Start');

        $activator = new Exact('/start');
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function testDoesNotMatchCaseInsensitive(): void
    {
        $this->message->shouldReceive('getText')->andReturn('/Start');

        $activator = new Exact('/stop');
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }

    public function testEmptyMessage(): void
    {
        $this->message->shouldReceive('getText')->andReturn(null);

        $activator = new Exact('/start');
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }
}
