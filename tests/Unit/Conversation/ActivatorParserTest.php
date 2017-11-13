<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Activator;
use FondBot\Conversation\Activators\In;
use FondBot\Conversation\ActivatorParser;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\Contains;

class ActivatorParserTest extends TestCase
{
    public function testCanParseStringsAndObjects(): void
    {
        $result = ActivatorParser::parse([
            'exact:foo,true',
            'in:foo,bar',
            'contains:foo,bar',
            Activator::exact('bar'),
        ]);

        $this->assertCount(4, $result);

        $this->assertInstanceOf(Exact::class, $result[0]);
        $this->assertAttributeEquals('foo', 'value', $result[0]);
        $this->assertAttributeEquals(true, 'caseSensitive', $result[0]);

        $this->assertInstanceOf(In::class, $result[1]);
        $this->assertAttributeEquals(['foo', 'bar'], 'values', $result[1]);

        $this->assertInstanceOf(Contains::class, $result[2]);
        $this->assertAttributeEquals(['foo', 'bar'], 'needles', $result[2]);

        $this->assertInstanceOf(Exact::class, $result[3]);
        $this->assertAttributeEquals('bar', 'value', $result[3]);
        $this->assertAttributeEquals(false, 'caseSensitive', $result[3]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Activator `foo` does not exist.
     */
    public function testThrowsInvalidArgumentExceptionIfActivatorCouldNotBeResolved(): void
    {
        ActivatorParser::parse([
            'foo:bar',
        ]);
    }
}
