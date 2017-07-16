<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Activators\InArray;

/**
 * @property mixed|\Mockery\Mock message
 */
class InArrayTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->message = $this->mock(ReceivedMessage::class);
    }

    public function testArrayMatches(): void
    {
        $this->message->shouldReceive('getText')->andReturn('/start');

        $activator = new InArray(['/bye', '/start', '/test']);
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function testArrayDoesNotMatch(): void
    {
        $this->message->shouldReceive('getText')->andReturn('/stop');

        $activator = new InArray(['/bye', '/start', '/test']);
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }

    public function testCollectionMatches(): void
    {
        $this->message->shouldReceive('getText')->andReturn('/start');

        $activator = new InArray(collect(['/bye', '/start', '/test']));
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function testCollectionDoesNotMatch(): void
    {
        $this->message->shouldReceive('getText')->andReturn('/stop');

        $activator = new InArray(collect(['/bye', '/start', '/test']));
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }
}
