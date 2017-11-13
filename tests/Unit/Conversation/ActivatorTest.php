<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Activator;
use FondBot\Conversation\Activators\In;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\Regex;
use FondBot\Conversation\Activators\Payload;
use FondBot\Conversation\Activators\Contains;
use FondBot\Conversation\Activators\Attachment;

class ActivatorTest extends TestCase
{
    public function testExact(): void
    {
        $this->assertInstanceOf(Exact::class, Activator::exact($this->faker()->word));
    }

    public function testContains(): void
    {
        $this->assertInstanceOf(Contains::class, Activator::contains($this->faker()->word));
    }

    public function testRegex(): void
    {
        $this->assertInstanceOf(Regex::class, Activator::regex($this->faker()->word));
    }

    public function testInArray(): void
    {
        $this->assertInstanceOf(In::class, Activator::in([1, 2, 3]));
    }

    public function testAttachment(): void
    {
        $this->assertInstanceOf(Attachment::class, Activator::attachment());
        $this->assertInstanceOf(Attachment::class, Activator::attachment($this->faker()->word));
    }

    public function testPayload(): void
    {
        $this->assertInstanceOf(Payload::class, Activator::payload($this->faker()->word));
    }
}
