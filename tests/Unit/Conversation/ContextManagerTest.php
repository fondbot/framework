<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use Illuminate\Cache\Repository;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Sender;
use FondBot\Conversation\ContextManager;
use Tests\Classes\Fakes\FakeSenderMessage;
use FondBot\Contracts\Database\Entities\Channel;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $driver
 * @property Channel                                    $channel
 * @property Sender                                     $sender
 * @property \Tests\Classes\Fakes\FakeSenderMessage     $message
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $cache
 * @property ContextManager                             manager
 */
class ContextManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->driver = $this->mock(Driver::class);
        $this->channel = new Channel([
            'name' => $this->faker()->word,
        ]);
        $this->sender = Sender::create($this->faker()->uuid, $this->faker()->name, $this->faker()->userName);
        $this->message = FakeSenderMessage::create();
        $this->cache = $this->mock(Repository::class);

        $this->manager = new ContextManager($this->cache);
    }

    public function test_resolve()
    {
        $this->driver->shouldReceive('getSender')->andReturn($this->sender)->once();
        $this->driver->shouldReceive('getMessage')->andReturn($this->message)->once();

        $key = 'context.'.$this->channel->name.'.'.$this->sender->getIdentifier();

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
        $context->shouldReceive('getSender')->andReturn($this->sender)->atLeast()->once();
        $context->shouldReceive('toArray')->andReturn($contextArray)->atLeast()->once();

        $key = 'context.'.$this->channel->name.'.'.$this->sender->getIdentifier();

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
        $context->shouldReceive('getSender')->andReturn($this->sender)->once();
        $context->shouldReceive('toArray')->andReturn($contextArray)->atLeast()->once();

        $key = 'context.'.$this->channel->name.'.'.$this->sender->getIdentifier();

        $this->cache->shouldReceive('forget')->with($key)->once();

        $this->manager->clear($context);
    }
}
