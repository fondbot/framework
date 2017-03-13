<?php
declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Cache;
use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\Objects\Participant;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use Tests\TestCase;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface participant
 * @property ContextManager manager
 */
class ContextManagerTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();

        $this->driver = $this->mock(Driver::class);
        $this->participant = $this->mock(Participant::class);

        $this->manager = new ContextManager;
    }

    public function test_resolve()
    {
        $this->driver->shouldReceive('getChannelName')->andReturn($channelName = $this->faker()->word);
        $this->driver->shouldReceive('getParticipant')->andReturn($this->participant);
        $this->participant->shouldReceive('getIdentifier')->andReturn($participantId = $this->faker()->uuid);

        $key = 'context.' . $channelName . '.' . $participantId;

        Cache::shouldReceive('get')->with($key, null)->andReturn([
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
        $this->driver->shouldReceive('getParticipant')->andReturn($this->participant);
        $this->participant->shouldReceive('getIdentifier')->andReturn($participantId = $this->faker()->uuid);

        $context = $this->mock(Context::class);
        $context->shouldReceive('getDriver')->andReturn($this->driver);
        $context->shouldReceive('getStory')->andReturn(null);
        $context->shouldReceive('getInteraction')->andReturn(null);
        $context->shouldReceive('getValues')->andReturn($values = ['key1' => 'value1']);

        $key = 'context.' . $channelName . '.' . $participantId;

        Cache::shouldReceive('put')->with($key, [
            'story' => null,
            'interaction' => null,
            'values' => $values,
        ])->once();

        $this->manager->save($context);
    }

}