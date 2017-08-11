<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Activators\WithPayload;

class WithPayloadTest extends TestCase
{
    public function testMatches(): void
    {
        $message = new MessageReceived('/start', null, null, 'foo');

        $activator = new WithPayload('foo');

        $this->assertTrue($activator->matches($message));
    }

    public function testDoesNotMatch(): void
    {
        $message = new MessageReceived('/start', null, null, 'foo');

        $activator = new WithPayload('bar');

        $this->assertFalse($activator->matches($message));
    }
}
