<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Activators\InArray;

class InArrayTest extends TestCase
{
    public function testConstructor(): void
    {
        $activator = new InArray(['foo', 'bar'], false);

        $this->assertAttributeEquals(['foo', 'bar'], 'values', $activator);
        $this->assertAttributeEquals(false, 'strict', $activator);
    }

    public function testArrayMatches(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/start');

        $activator = new InArray(['/bye', '/start', '/test']);
        $this->assertTrue(
            $activator->matches($message)
        );
    }

    public function testArrayDoesNotMatch(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/stop');

        $activator = new InArray(['/bye', '/start', '/test']);

        $this->assertFalse($activator->matches($message));
    }

    public function testCollectionMatches(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/start');

        $activator = new InArray(collect(['/bye', '/start', '/test']));

        $this->assertTrue($activator->matches($message));
    }

    public function testCollectionDoesNotMatch(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/stop');

        $activator = new InArray(collect(['/bye', '/start', '/test']));

        $this->assertFalse($activator->matches($message));
    }
}
