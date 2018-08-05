<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Activators\Is;

class IsTest extends TestCase
{
    public function testStringMatches(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), 'abc');

        $activator = Is::make('abc');

        $this->assertTrue($activator->matches($message));
    }

    public function testStringDoesNotMatch(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), 'ab');

        $activator = Is::make('abc');

        $this->assertFalse($activator->matches($message));
    }
}
