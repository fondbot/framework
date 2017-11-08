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
    public function testSuccess(): void
    {
        $parser = new ActivatorParser([
            'exact:foo',
            'in_array:1,2',
            Activator::exact('bar', true),
        ]);

        $result = $parser->getResult();

        $this->assertCount(3, $result);

        $this->assertInstanceOf(Exact::class, $result[0]);
        $this->assertInstanceOf(InArray::class, $result[1]);
        $this->assertInstanceOf(Exact::class, $result[2]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Activator `foo` does not exist.
     */
    public function testThrowsInvalidArgumentExceptionIfActivatorCouldNotBeResolved(): void
    {
        $parser = new ActivatorParser([
            'foo:bar',
        ]);

        $parser->getResult();
    }
}
