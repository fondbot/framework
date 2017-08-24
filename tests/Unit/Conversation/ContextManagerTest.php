<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;

class ContextManagerTest extends TestCase
{
    public function testLoad(): void
    {
        $channel = $this->mock(Channel::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);
        $items = ['foo' => 'bar'];

        $channel->shouldReceive('getName')->andReturn('foo')->once();
        $chat->shouldReceive('getId')->andReturn('bar')->once();
        $user->shouldReceive('getId')->andReturn('baz')->once();

        $this->cache()->forever('context.foo.bar.baz', compact('chat', 'user', 'items'));

        $result = (new ContextManager($this->cache()))->load($channel, $chat, $user);

        $this->assertInstanceOf(Context::class, $result);
        $this->assertSame($channel, $result->getChannel());
        $this->assertSame($chat, $result->getChat());
        $this->assertSame($user, $result->getUser());
        $this->assertSame($items, $result->toArray());
    }

    public function testSave(): void
    {
        $context = $this->mock(Context::class);
        $channel = $this->mock(Channel::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);
        $items = ['foo' => 'bar'];

        $context->shouldReceive('getChannel')->once()->andReturn($channel);
        $context->shouldReceive('getChat')->once()->andReturn($chat);
        $context->shouldReceive('getUser')->once()->andReturn($user);
        $context->shouldReceive('toArray')->once()->andReturn($items);

        $channel->shouldReceive('getName')->once()->andReturn('foo');
        $chat->shouldReceive('getId')->once()->andReturn('bar');
        $user->shouldReceive('getId')->once()->andReturn('baz');

        (new ContextManager($this->cache()))->save($context);

        $this->assertSame($items, $this->cache()->get('context.foo.bar.baz'));
    }

    public function testClear(): void
    {
        $context = $this->mock(Context::class);
        $channel = $this->mock(Channel::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);
        $items = ['foo' => 'bar'];

        $context->shouldReceive('getChannel')->once()->andReturn($channel);
        $context->shouldReceive('getChat')->once()->andReturn($chat);
        $context->shouldReceive('getUser')->once()->andReturn($user);

        $channel->shouldReceive('getName')->once()->andReturn('foo');
        $chat->shouldReceive('getId')->once()->andReturn('bar');
        $user->shouldReceive('getId')->once()->andReturn('baz');

        $this->cache()->forever('context.foo.bar.baz', $items);

        (new ContextManager($this->cache()))->clear($context);

        $this->assertNull($this->cache()->get('context.foo.bar.baz'));
    }
}
