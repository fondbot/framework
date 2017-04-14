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
    protected function setUp()
    {
        parent::setUp();

        $this->message = $this->mock(ReceivedMessage::class);
    }

    public function test_array_matches()
    {
        $this->message->shouldReceive('getText')->andReturn('/start');

        $activator = new InArray(['/bye', '/start', '/test']);
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function test_array_does_not_match()
    {
        $this->message->shouldReceive('getText')->andReturn('/stop');

        $activator = new InArray(['/bye', '/start', '/test']);
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }

    public function test_collection_matches()
    {
        $this->message->shouldReceive('getText')->andReturn('/start');

        $activator = new InArray(collect(['/bye', '/start', '/test']));
        $this->assertTrue(
            $activator->matches($this->message)
        );
    }

    public function test_collection_does_not_match()
    {
        $this->message->shouldReceive('getText')->andReturn('/stop');

        $activator = new InArray(collect(['/bye', '/start', '/test']));
        $this->assertFalse(
            $activator->matches($this->message)
        );
    }
}
