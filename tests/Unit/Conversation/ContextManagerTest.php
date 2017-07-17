<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use Psr\SimpleCache\CacheInterface;
use FondBot\Conversation\ContextManager;

class ContextManagerTest extends TestCase
{
    public function testLoad(): void
    {
        $channel = $this->mock(Channel::class);
        $cache = $this->mock(CacheInterface::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);
        $driver = $this->mock(Driver::class);

        $driver->shouldReceive('getChat')->andReturn($chat)->once();
        $driver->shouldReceive('getUser')->andReturn($user)->once();
        $cache->shouldReceive('get')->andReturn(['chat' => $channel, 'user' => $chat, 'items' => $user])->once();

        $channel->shouldReceive('getName')->andReturn('foo')->once();
        $chat->shouldReceive('getId')->andReturn($this->faker()->uuid)->once();
        $user->shouldReceive('getId')->andReturn($this->faker()->uuid)->once();

        (new ContextManager($cache))->load($channel, $driver);
    }

    public function testSave(): void
    {
        $cache = $this->mock(CacheInterface::class);
        $context = $this->mock(Context::class);
        $channel = $this->mock(Channel::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);

        $context->shouldReceive('getChannel')->once()->andReturn($channel);
        $context->shouldReceive('getChat')->once()->andReturn($chat);
        $context->shouldReceive('getUser')->once()->andReturn($user);
        $context->shouldReceive('toArray')->once();

        $channel->shouldReceive('getName')->once()->andReturn('foo');
        $chat->shouldReceive('getId')->once()->andReturn($this->faker()->uuid);
        $user->shouldReceive('getId')->once()->andReturn($this->faker()->uuid);

        $cache->shouldReceive('set')->once();

        (new ContextManager($cache))->save($context);
    }

    public function testClear(): void
    {
        $cache = $this->mock(CacheInterface::class);
        $context = $this->mock(Context::class);
        $channel = $this->mock(Channel::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);

        $context->shouldReceive('getChannel')->once()->andReturn($channel);
        $context->shouldReceive('getChat')->once()->andReturn($chat);
        $context->shouldReceive('getUser')->once()->andReturn($user);

        $channel->shouldReceive('getName')->once()->andReturn('foo');
        $chat->shouldReceive('getId')->once()->andReturn($this->faker()->uuid);
        $user->shouldReceive('getId')->once()->andReturn($this->faker()->uuid);

        $cache->shouldReceive('delete')->once();
        (new ContextManager($cache))->clear($context);
    }
}
