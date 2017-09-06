<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Activators;

use FondBot\Tests\TestCase;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Activators\Exact;

class ExactTest extends TestCase
{
    public function testMatchesCaseSensitive(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/start');

        $activator = new Exact('/start', true);

        $this->assertTrue($activator->matches($message));
    }

    public function testDoesNotMatchCaseSensitive(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/Start');

        $activator = new Exact('/start', true);

        $this->assertFalse($activator->matches($message));
    }

    public function testMatchesCaseInsensitive(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/Start');

        $activator = new Exact('/start');

        $this->assertTrue($activator->matches($message));
    }

    public function testDoesNotMatchCaseInsensitive(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '/Start');

        $activator = new Exact('/stop');

        $this->assertFalse($activator->matches($message));
    }

    public function testEmptyMessage(): void
    {
        $message = new MessageReceived($this->fakeChat(), $this->fakeUser(), '');

        $activator = new Exact('/start');

        $this->assertFalse($activator->matches($message));
    }
}
