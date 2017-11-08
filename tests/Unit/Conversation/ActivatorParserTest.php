<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Activator;
use FondBot\Conversation\ActivatorParser;
use FondBot\Conversation\Activators\Exact;

class ActivatorParserTest extends TestCase
{
    public function testSuccess(): void
    {
        $parser = new ActivatorParser([
           'exact:foo',
            Activator::exact('bar', true),
        ]);

        $result = $parser->getResult();

        $this->assertCount(2, $result);

        $this->assertInstanceOf(Exact::class, $result[0]);
        $this->assertInstanceOf(Exact::class, $result[1]);
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
