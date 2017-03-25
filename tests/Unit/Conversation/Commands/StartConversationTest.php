<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Commands;

use Bus;
use Tests\TestCase;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Sender;
use FondBot\Conversation\StoryManager;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\Commands\StoreMessage;
use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Conversation\ConversationManager;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Conversation\Commands\StartConversation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $channelManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $contextManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $storyManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $conversationManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $sender
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $message
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $context
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $story
 * @property Channel                                    $channel
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
        $this->driver = $this->mock(Driver::class);
        $this->sender = $this->mock(Sender::class);
        $this->message = $this->mock(SenderMessage::class);
        $this->channel = $this->factory(Channel::class)->create();
    }

    public function test_story_found()
    {
        $this->driver->shouldReceive('getSender')->andReturn($this->sender)->once();
        $this->driver->shouldReceive('getMessage')->andReturn($this->message)->atLeast()->once();

        $this->channelManager->shouldReceive('createDriver')
            ->with($this->channel, [], [])
            ->andReturn($this->driver)
            ->once();

        $this->contextManager->shouldReceive('resolve')->with($this->channel,
            $this->driver)->andReturn($this->context)->once();
        $this->storyManager->shouldReceive('find')
            ->with($this->context, $this->driver->getMessage())
            ->andReturn($this->story)
            ->once();
        $this->conversationManager->shouldReceive('start')->with($this->driver, $this->context, $this->story)->once();

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
        $this->driver->shouldReceive('getSender')->andReturn($this->sender)->once();
        $this->driver->shouldReceive('getMessage')->andReturn($this->message)->atLeast()->once();

        $this->channelManager->shouldReceive('createDriver')
            ->with($this->channel, [], [])
            ->andReturn($this->driver)
            ->once();

        $this->contextManager->shouldReceive('resolve')->with($this->channel,
            $this->driver)->andReturn($this->context)->once();

        $this->storyManager->shouldReceive('find')->with($this->context, $this->message)->andReturn(null)->once();
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
