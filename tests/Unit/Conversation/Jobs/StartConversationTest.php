<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Jobs;

use Bus;
use Tests\TestCase;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use Tests\Classes\Fakes\FakeDriver;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\Driver;
use FondBot\Conversation\StoryManager;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\Jobs\StoreMessage;
use FondBot\Conversation\ConversationManager;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Conversation\Jobs\StartConversation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface channelManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface contextManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface storyManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface conversationManager
 * @property Driver                                     driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface context
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface story
 * @property Channel                                    channel
 */
class StartConversationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        Bus::fake();

        $this->channelManager = $this->mock(ChannelManager::class);
        $this->contextManager = $this->mock(ContextManager::class);
        $this->storyManager = $this->mock(StoryManager::class);
        $this->conversationManager = $this->mock(ConversationManager::class);
        $this->context = $this->mock(Context::class);
        $this->story = $this->mock(Story::class);
        $this->driver = new FakeDriver;
        $this->channel = Channel::firstOrCreate([
            'driver' => get_class($this->driver),
            'name' => $this->faker()->word,
            'parameters' => [],
        ]);
    }

    public function test_story_found()
    {
        $this->channelManager->shouldReceive('createDriver')
            ->with($this->channel, [], [])
            ->andReturn($this->driver)
            ->once();

        $this->contextManager->shouldReceive('resolve')->with($this->driver)->andReturn($this->context)->once();
        $this->storyManager->shouldReceive('find')
            ->with($this->context, $this->driver->getMessage())
            ->andReturn($this->story)
            ->once();
        $this->conversationManager->shouldReceive('start')->with($this->context, $this->story)->once();

        $job = new StartConversation($this->channel, [], []);
        $job->handle(
            $this->channelManager,
            $this->contextManager,
            $this->storyManager,
            $this->conversationManager
        );

        Bus::assertDispatched(StoreMessage::class);
    }

    public function test_no_story_found()
    {
        $message = $this->driver->getMessage();

        $this->channelManager->shouldReceive('createDriver')
            ->with($this->channel, [], [])
            ->andReturn($this->driver)
            ->once();

        $this->contextManager->shouldReceive('resolve')->with($this->driver)->andReturn($this->context)->once();

        $this->storyManager->shouldReceive('find')->with($this->context, $message)->andReturn(null)->once();
        $this->conversationManager->shouldReceive('start')->never();

        $job = new StartConversation($this->channel, [], []);
        $job->handle(
            $this->channelManager,
            $this->contextManager,
            $this->storyManager,
            $this->conversationManager
        );

        Bus::assertDispatched(StoreMessage::class);
    }
}
