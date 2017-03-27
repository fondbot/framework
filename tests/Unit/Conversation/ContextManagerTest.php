<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use Illuminate\Cache\Repository;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\Driver;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Channels\ReceivedMessage;

/**
 * @property string                                     $channel
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $sender
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $receivedMessage
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $cache
 * @property ContextManager                             $manager
 */
class ContextManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->channel = $this->faker()->userName;
        $this->sender = $this->mock(User::class);
        $this->receivedMessage = $this->mock(ReceivedMessage::class);
        $this->driver = $this->mock(Driver::class);
        $this->cache = $this->mock(Repository::class);

        $this->manager = new ContextManager($this->cache);
    }

    public function test_resolve()
    {
        $this->driver->shouldReceive('getUser')->andReturn($this->sender)->once();
        $this->driver->shouldReceive('getMessage')->andReturn($this->receivedMessage)->once();
        $this->sender->shouldReceive('getId')->andReturn($senderId = $this->faker()->uuid)->atLeast()->once();

        $key = 'context.'.$this->channel.'.'.$senderId;

        $this->cache->shouldReceive('get')->with($key)->andReturn([
            'story' => null,
            'interaction' => null,
            'values' => $values = [
                'username' => $this->faker()->userName,
                'uuid' => $this->faker()->uuid,
            ],
        ])->once();

        $context = $this->manager->resolve($this->channel, $this->driver);

        $this->assertInstanceOf(Context::class, $context);
    }

    public function test_save()
    {
        $contextArray = [
            'story' => null,
            'interaction' => null,
            'values' => ['key1' => 'value1'],
        ];

        $context = $this->mock(Context::class);
        $context->shouldReceive('getChannel')->andReturn($this->channel)->atLeast()->once();
        $context->shouldReceive('getUser')->andReturn($this->sender)->atLeast()->once();
        $context->shouldReceive('toArray')->andReturn($contextArray)->atLeast()->once();
        $this->sender->shouldReceive('getId')->andReturn($senderId = $this->faker()->uuid)->atLeast()->once();

        $key = 'context.'.$this->channel.'.'.$senderId;

        $this->cache->shouldReceive('forever')->with($key, $contextArray)->once();

        $this->manager->save($context);
    }

    public function test_clear()
    {
        $contextArray = [
            'story' => null,

            'interaction' => null,
            'values' => ['key1' => 'value1'],
        ];

        $context = $this->mock(Context::class);
        $context->shouldReceive('getChannel')->andReturn($this->channel)->once();
        $context->shouldReceive('getUser')->andReturn($this->sender)->once();
        $context->shouldReceive('toArray')->andReturn($contextArray)->atLeast()->once();
        $this->sender->shouldReceive('getId')->andReturn($senderId = $this->faker()->uuid)->atLeast()->once();

        $key = 'context.'.$this->channel.'.'.$senderId;

        $this->cache->shouldReceive('forget')->with($key)->once();

        $this->manager->clear($context);
    }
}
