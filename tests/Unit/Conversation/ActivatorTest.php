<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Activator;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\Regex;
use FondBot\Conversation\Activators\InArray;
use FondBot\Conversation\Activators\Contains;
use FondBot\Conversation\Activators\WithPayload;
use FondBot\Conversation\Activators\WithAttachment;

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
        $this->assertInstanceOf(InArray::class, Activator::inArray([1, 2, 3]));
    }

    public function testWithAttachment(): void
    {
        $this->assertInstanceOf(WithAttachment::class, Activator::withAttachment());
        $this->assertInstanceOf(WithAttachment::class, Activator::withAttachment($this->faker()->word));
    }

    public function testWithPayload(): void
    {
        $this->assertInstanceOf(WithPayload::class, Activator::withPayload($this->faker()->word));
    }
}
