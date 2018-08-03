<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Concerns;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Conversation\Concerns\InteractsWithContext;

class InteractsWithContextTest extends TestCase
{
    use InteractsWithContext;

    public function testGetChannel()
    {
        $channel = $this->mock(Channel::class);

        $this->setContext(
            new Context(
                $channel,
                $this->mock(Chat::class),
                $this->mock(User::class),
                ['foo' => 'bar']
            )
        );

        $this->assertSame($channel, $this->getChannel());
    }

    public function testGetChat()
    {
        $chat = $this->mock(Chat::class);

        $this->setContext(
            new Context(
                $this->mock(Channel::class),
                $chat,
                $this->mock(User::class),
                ['foo' => 'bar']
            )
        );

        $this->assertSame($chat, $this->getChat());
    }

    public function testGetUser()
    {
        $user = $this->mock(User::class);

        $this->setContext(
            new Context(
                $this->mock(Channel::class),
                $this->mock(Chat::class),
                $user,
                ['foo' => 'bar']
            )
        );

        $this->assertSame($user, $this->getUser());
    }

    public function testContext(): void
    {
        $this->setContext(
            new Context(
                $this->mock(Channel::class),
                $this->mock(Chat::class),
                $this->mock(User::class),
                ['foo' => 'bar']
            )
        );

        $this->assertSame('bar', $this->context('foo'));
        $this->assertNull($this->context('bar'));
        $this->assertSame('foo', $this->context('bar', 'foo'));
        $this->assertInstanceOf(Context::class, $this->context());
    }

    public function testRemember(): void
    {
        $this->setContext(
            new Context(
                $this->mock(Channel::class),
                $this->mock(Chat::class),
                $this->mock(User::class),
                ['foo' => 'bar']
            )
        );

        $this->remember('some', 'value');

        $this->assertSame('value', $this->context('some'));
    }
}
