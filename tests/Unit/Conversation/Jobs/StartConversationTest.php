<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Jobs;

use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Classes\Fakes\FakeDriver;
use Tests\TestCase;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\Driver;
use FondBot\Conversation\StoryManager;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Events\MessageReceived;
use FondBot\Conversation\ConversationManager;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Conversation\Jobs\StartConversation;
use FondBot\Contracts\Database\Entities\Participant;
use FondBot\Contracts\Database\Services\ParticipantService;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface       channelManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface       contextManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface       storyManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface       conversationManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface       participantService
 * @property Driver                                           driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface       context
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface       story
 * @property Channel                                          channel
 * @property \FondBot\Contracts\Database\Entities\Participant participant
 */
class StartConversationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->channelManager = $this->mock(ChannelManager::class);
        $this->contextManager = $this->mock(ContextManager::class);
        $this->storyManager = $this->mock(StoryManager::class);
        $this->conversationManager = $this->mock(ConversationManager::class);
        $this->participantService = $this->mock(ParticipantService::class);
        $this->driver = new FakeDriver;
        $this->context = $this->mock(Context::class);
        $this->story = $this->mock(Story::class);
        $this->channel = Channel::firstOrCreate([
            'driver' => get_class($this->driver),
            'name' => $this->faker()->word,
            'parameters' => [],
        ]);
        $this->participant = new Participant;
    }

    public function test_story_found()
    {
        $this->channelManager->shouldReceive('createDriver')->with($this->channel, [],
            [])->andReturn($this->driver)->once();

        $sender = $this->driver->getSender();
        $message = $this->driver->getMessage();

        $this->participantService->shouldReceive('createOrUpdate')
            ->with(
                [
                    'channel_id' => $this->channel->id,
                    'identifier' => $sender->getIdentifier(),
                    'name' => $sender->getName(),
                    'username' => $sender->getUsername(),
                ], ['channel_id' => $this->channel->id, 'identifier' => $sender->getIdentifier()]
            )
            ->andReturn($this->participant);

        $this->contextManager->shouldReceive('resolve')->with($this->driver)->andReturn($this->context)->once();

        $this->expectsEvents(MessageReceived::class);

        $this->storyManager->shouldReceive('find')->with($this->context, $message)->andReturn($this->story)->once();
        $this->conversationManager->shouldReceive('start')->with($this->context, $this->story)->once();

        $job = new StartConversation($this->channel, [], []);
        $job->handle(
            resolve(Dispatcher::class),
            $this->channelManager,
            $this->contextManager,
            $this->storyManager,
            $this->conversationManager,
            $this->participantService
        );
    }

    public function test_no_story_found()
    {
        $sender = $this->driver->getSender();
        $message = $this->driver->getMessage();

        $this->channelManager->shouldReceive('createDriver')
            ->with($this->channel, [], [])
            ->andReturn($this->driver)
            ->once();

        $this->participantService->shouldReceive('createOrUpdate')
            ->with(
                [
                    'channel_id' => $this->channel->id,
                    'identifier' => $sender->getIdentifier(),
                    'name' => $sender->getName(),
                    'username' => $sender->getUsername(),
                ], ['channel_id' => $this->channel->id, 'identifier' => $sender->getIdentifier()]
            )
            ->andReturn($this->participant);

        $this->contextManager->shouldReceive('resolve')->with($this->driver)->andReturn($this->context)->once();

        $this->expectsEvents(MessageReceived::class);

        $this->storyManager->shouldReceive('find')->with($this->context, $message)->andReturn(null)->once();
        $this->conversationManager->shouldReceive('start')->never();

        $job = new StartConversation($this->channel, [], []);
        $job->handle(
            resolve(Dispatcher::class),
            $this->channelManager,
            $this->contextManager,
            $this->storyManager,
            $this->conversationManager,
            $this->participantService
        );
    }
}
