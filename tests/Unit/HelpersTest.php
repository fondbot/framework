<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Tests\Mocks\FakeDriver;

class HelpersTest extends TestCase
{
    public function testKernel(): void
    {
        $this->assertSame($this->kernel, kernel());
    }

    public function testContext(): void
    {
        $this->assertNull(context());

        $context = new Context(
            new Channel('foo', new FakeDriver()),
            new Chat('1'),
            new User('2')
        );

        $context->setItem('bar', 'baz');

        $this->app->bind('fondbot.conversation.context', function () use (&$context) {
            return $context;
        });

        $this->assertSame($context, context());
        $this->assertNull(context('foo'));
        $this->assertSame('baz', context('bar'));
    }
}
