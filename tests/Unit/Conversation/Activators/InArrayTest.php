<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Activators\In;

class InArrayTest extends TestCase
{
    public function testMake(): void
    {
        $activator = In::make(['foo', 'bar']);

        $this->assertAttributeEquals(['foo', 'bar'], 'values', $activator);
    }

    public function testArrayMatches(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/start');

        $activator = In::make(['/bye', '/start', '/test']);
        $this->assertTrue(
            $activator->matches($message)
        );
    }

    public function testArrayDoesNotMatch(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/stop');

        $activator = In::make(['/bye', '/start', '/test']);

        $this->assertFalse($activator->matches($message));
    }

    public function testCollectionMatches(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/start');

        $activator = In::make(collect(['/bye', '/start', '/test']));

        $this->assertTrue($activator->matches($message));
    }

    public function testCollectionDoesNotMatch(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/stop');

        $activator = In::make(collect(['/bye', '/start', '/test']));

        $this->assertFalse($activator->matches($message));
    }
}
