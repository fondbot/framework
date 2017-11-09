<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Activators\Payload;

class PayloadTest extends TestCase
{
    public function testMatches(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/start', null, null, 'foo');

        $activator = new Payload('foo');

        $this->assertTrue($activator->matches($message));
    }

    public function testDoesNotMatch(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/start', null, null, 'foo');

        $activator = new Payload('bar');

        $this->assertFalse($activator->matches($message));
    }
}
