<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use FondBot\Channels\Driver;
use FondBot\Channels\Sender;
use Illuminate\Cache\Repository;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $sender
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface cache
 * @property ContextManager manager
 */
class ContextManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->driver = $this->mock(Driver::class);
        $this->sender = $this->mock(Sender::class);
        $this->cache = $this->mock(Repository::class);

        $this->manager = new ContextManager($this->cache);
    }

    public function test_resolve()
    {
        $this->driver->shouldReceive('getChannelName')->andReturn($channelName = $this->faker()->word);
        $this->driver->shouldReceive('getSender')->andReturn($this->sender);
        $this->sender->shouldReceive('getIdentifier')->andReturn($senderId = $this->faker()->uuid);

        $key = 'context.'.$channelName.'.'.$senderId;

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
        $this->driver->shouldReceive('getChannelName')->andReturn($channelName = $this->faker()->word);
        $this->driver->shouldReceive('getSender')->andReturn($this->sender);
        $this->sender->shouldReceive('getIdentifier')->andReturn($senderId = $this->faker()->uuid);

        $context = $this->mock(Context::class);
        $context->shouldReceive('getDriver')->andReturn($this->driver);
        $context->shouldReceive('getStory')->andReturn(null);
        $context->shouldReceive('getInteraction')->andReturn(null);
        $context->shouldReceive('getValues')->andReturn($values = ['key1' => 'value1']);

        $key = 'context.'.$channelName.'.'.$senderId;

        $this->cache->shouldReceive('forever')->with($key, [
            'story' => null,
            'interaction' => null,
            'values' => $values,
        ])->once();

        $this->manager->save($context);
    }
}
