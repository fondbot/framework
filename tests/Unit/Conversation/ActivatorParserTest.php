<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Activator;
use FondBot\Conversation\ActivatorParser;
use FondBot\Conversation\Activators\Exact;
use FondBot\Conversation\Activators\InArray;

class ActivatorParserTest extends TestCase
{
    public function testCanParseStringsAndObjects(): void
    {
        $result = ActivatorParser::parse([
            'exact:foo,true',
            'in_array:foo,bar',
            Activator::exact('bar'),
        ]);

        $this->assertCount(3, $result);

        $this->assertInstanceOf(Exact::class, $result[0]);
        $this->assertAttributeEquals('foo', 'value', $result[0]);
        $this->assertAttributeEquals(true, 'caseSensitive', $result[0]);

        $this->assertInstanceOf(InArray::class, $result[1]);
        $this->assertAttributeEquals(['foo', 'bar'], 'values', $result[1]);

        $this->assertInstanceOf(Exact::class, $result[2]);
        $this->assertAttributeEquals('bar', 'value', $result[2]);
        $this->assertAttributeEquals(false, 'caseSensitive', $result[2]);
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
