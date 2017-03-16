<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use FondBot\Channels\Driver;
use FondBot\Channels\Sender;
use Illuminate\Cache\Repository;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Database\Entities\Channel;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $driver
 * @property Channel $channel
 * @property Sender $sender
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $cache
 * @property ContextManager manager
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
        $this->cache = $this->mock(Repository::class);

        $this->manager = new ContextManager($this->cache);
    }

    public function test_resolve()
    {
        $this->driver->shouldReceive('getChannel')->andReturn($this->channel);
        $this->driver->shouldReceive('getSender')->andReturn($this->sender);

        $key = 'context.'.$this->channel->name.'.'.$this->sender->getIdentifier();

        $this->cache->shouldReceive('get')->with($key)->andReturn([
            'story' => null,
            'interaction' => null,
            'values' => $values = [
                'username' => $this->faker()->userName,
                'uuid' => $this->faker()->uuid,
            ],
        ])->once();

        $context = $this->manager->resolve($this->driver);

        $this->assertInstanceOf(Context::class, $context);
        $this->assertSame($this->driver, $context->getDriver());
        $this->assertNull($context->getStory());
        $this->assertNull($context->getInteraction());
        $this->assertSame($values, $context->getValues());
    }

    public function test_save()
    {
        $this->driver->shouldReceive('getChannel')->andReturn($this->channel);
        $this->driver->shouldReceive('getSender')->andReturn($this->sender);

        $context = $this->mock(Context::class);
        $context->shouldReceive('getDriver')->andReturn($this->driver);
        $context->shouldReceive('getStory')->andReturn(null);
        $context->shouldReceive('getInteraction')->andReturn(null);
        $context->shouldReceive('getValues')->andReturn($values = ['key1' => 'value1']);

        $key = 'context.'.$this->channel->name.'.'.$this->sender->getIdentifier();

        $this->cache->shouldReceive('forever')->with($key, [
            'story' => null,
            'interaction' => null,
            'values' => $values,
        ])->once();

        $this->manager->save($context);
    }
}
