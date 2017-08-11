<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Activators\Pattern;

class PatternTest extends TestCase
{
    public function testStringMatches(): void
    {
        $message = new MessageReceived('abc');

        $activator = new Pattern('/abc/');

        $this->assertTrue($activator->matches($message));
    }

    public function testStringDoesNotMatch(): void
    {
        $message = new MessageReceived('ab');

        $activator = new Pattern('/abc/');

        $this->assertFalse($activator->matches($message));
    }
}
