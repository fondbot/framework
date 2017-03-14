<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use FondBot\Channels\Driver;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use FondBot\Jobs\StartConversation;
use FondBot\Channels\ChannelManager;
use FondBot\Channels\Objects\Message;
use FondBot\Conversation\StoryManager;
use FondBot\Database\Entities\Channel;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\ConversationManager;

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
        $driver = $this->mock(Driver::class);
        $context = $this->mock(Context::class);
        $message = $this->mock(Message::class);
        $story = $this->mock(Story::class);

        $driver->shouldReceive('getMessage')->andReturn($message);
        $channelManager->shouldReceive('createDriver')->with($request, $channel)->andReturn($driver)->once();
        $contextManager->shouldReceive('resolve')->with($driver)->andReturn($context)->once();
        $storyManager->shouldReceive('find')->with($context, $message)->andReturn($story)->once();
        $conversationManager->shouldReceive('start')->with($context, $driver, $channel, $story)->once();

        $job = new StartConversation($channel, $request);
        $job->handle($channelManager, $contextManager, $storyManager, $conversationManager);
    }
}
