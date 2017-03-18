<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use FondBot\Channels\Sender;
use FondBot\Channels\Message;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use FondBot\Jobs\StartConversation;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\Driver;
use FondBot\Conversation\StoryManager;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Events\MessageReceived;
use FondBot\Conversation\ConversationManager;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use FondBot\Contracts\Database\Services\ParticipantService;

class StartConversationTest extends TestCase
{
    public function test_story_found()
    {
        $request = [];
        $headers = [];
        $channel = new Channel(['id' => random_int(1, time())]);
        $participant = new Participant;

        $channelManager = $this->mock(ChannelManager::class);
        $contextManager = $this->mock(ContextManager::class);
        $storyManager = $this->mock(StoryManager::class);
        $conversationManager = $this->mock(ConversationManager::class);
        $participantService = $this->mock(ParticipantService::class);
        $driver = $this->mock(Driver::class);
        $context = $this->mock(Context::class);
        $story = $this->mock(Story::class);

        $channelManager->shouldReceive('createDriver')->with($request, $headers, $channel)->andReturn($driver)->once();

        $driver->shouldReceive('getMessage')->andReturn(
            $message = Message::create($this->faker()->text)
        );
        $driver->shouldReceive('getSender')->andReturn(
            $sender = Sender::create($this->faker()->uuid, $this->faker()->name, $this->faker()->userName)
        );

        $participantService->shouldReceive('createOrUpdate')
            ->with(
                [
                    'channel_id' => $channel->id,
                    'identifier' => $sender->getIdentifier(),
                    'name' => $sender->getName(),
                    'username' => $sender->getUsername(),
                ], ['channel_id' => $channel->id, 'identifier' => $sender->getIdentifier()]
            )
            ->andReturn($participant);

        $contextManager->shouldReceive('resolve')->with($driver)->andReturn($context)->once();

        $this->expectsEvents(MessageReceived::class);

        $storyManager->shouldReceive('find')->with($context, $message)->andReturn($story)->once();
        $conversationManager->shouldReceive('start')->with($context, $story)->once();

        $job = new StartConversation($channel, $request, $headers);
        $job->handle($channelManager, $contextManager, $storyManager, $conversationManager, $participantService);
    }

    public function test_no_story_found()
    {
        $request = [];
        $headers = [];
        $channel = new Channel(['id' => random_int(1, time())]);
        $participant = new Participant;

        $channelManager = $this->mock(ChannelManager::class);
        $contextManager = $this->mock(ContextManager::class);
        $storyManager = $this->mock(StoryManager::class);
        $conversationManager = $this->mock(ConversationManager::class);
        $participantService = $this->mock(ParticipantService::class);
        $driver = $this->mock(Driver::class);
        $context = $this->mock(Context::class);

        $channelManager->shouldReceive('createDriver')->with($request, $headers, $channel)->andReturn($driver)->once();

        $driver->shouldReceive('getMessage')->andReturn(
            $message = Message::create($this->faker()->text)
        );
        $driver->shouldReceive('getSender')->andReturn(
            $sender = Sender::create($this->faker()->uuid, $this->faker()->name, $this->faker()->userName)
        );

        $participantService->shouldReceive('createOrUpdate')
            ->with(
                [
                    'channel_id' => $channel->id,
                    'identifier' => $sender->getIdentifier(),
                    'name' => $sender->getName(),
                    'username' => $sender->getUsername(),
                ], ['channel_id' => $channel->id, 'identifier' => $sender->getIdentifier()]
            )
            ->andReturn($participant);

        $contextManager->shouldReceive('resolve')->with($driver)->andReturn($context)->once();

        $this->expectsEvents(MessageReceived::class);

        $storyManager->shouldReceive('find')->with($context, $message)->andReturn(null)->once();
        $conversationManager->shouldReceive('start')->never();

        $job = new StartConversation($channel, $request, $headers);
        $job->handle($channelManager, $contextManager, $storyManager, $conversationManager, $participantService);
    }
}
