<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use FondBot\Channels\Driver;
use FondBot\Channels\Sender;
use FondBot\Channels\Message;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use FondBot\Jobs\StartConversation;
use FondBot\Channels\ChannelManager;
use FondBot\Conversation\StoryManager;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Events\MessageReceived;
use FondBot\Conversation\ConversationManager;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Services\ParticipantService;

class StartConversationTest extends TestCase
{
    public function test()
    {
        $request = [];
        $channel = $this->mock(Channel::class);

        $channelManager = $this->mock(ChannelManager::class);
        $contextManager = $this->mock(ContextManager::class);
        $storyManager = $this->mock(StoryManager::class);
        $conversationManager = $this->mock(ConversationManager::class);
        $participantService = $this->mock(ParticipantService::class);
        $driver = $this->mock(Driver::class);
        $context = $this->mock(Context::class);
        $story = $this->mock(Story::class);

        $channelManager->shouldReceive('createDriver')->with($request, $channel)->andReturn($driver)->once();

        $driver->shouldReceive('getMessage')->andReturn(
            $message = Message::create($this->faker()->text)
        );
        $driver->shouldReceive('getSender')->andReturn(
            $sender = Sender::create($this->faker()->uuid, $this->faker()->name, $this->faker()->userName)
        );

        $participantService->shouldReceive('findByChannelAndIdentifier')->with($channel, $sender->getIdentifier());

        $contextManager->shouldReceive('resolve')->with($driver)->andReturn($context)->once();

        $this->expectsEvents(MessageReceived::class);

        $storyManager->shouldReceive('find')->with($context, $message)->andReturn($story)->once();
        $conversationManager->shouldReceive('start')->with($context, $driver, $channel, $story)->once();

        $job = new StartConversation($channel, $request);
        $job->handle($channelManager, $contextManager, $storyManager, $conversationManager, $participantService);
    }
}
