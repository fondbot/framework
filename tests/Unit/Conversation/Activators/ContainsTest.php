<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Activators\Contains;

class ContainsTest extends TestCase
{
    public function testMake(): void
    {
        $activator = Contains::make(['foo', 'bar']);
        $this->assertAttributeEquals(['foo', 'bar'], 'needles', $activator);
    }

    public function testMatches(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), 'this is foo');

        $activator = Contains::make('foo');

        $this->assertTrue($activator->matches($message));
    }

    public function testDoesNotMatch(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), 'this is bar');

        $activator = Contains::make('foo');

        $this->assertFalse($activator->matches($message));
    }

    public function testMessageDoesNotHaveText(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '');

        $activator = Contains::make('foo');

        $this->assertFalse($activator->matches($message));
    }
}
