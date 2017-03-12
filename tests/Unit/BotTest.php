<?php
declare(strict_types=1);

namespace Tests\Unit;

use FondBot\Bot;
use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\ChannelManager;
use FondBot\Channels\Objects\Message;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\ConversationManager;
use FondBot\Conversation\Story;
use FondBot\Conversation\StoryManager;
use FondBot\Database\Entities\Channel;
use Tests\TestCase;

class BotTest extends TestCase
{

    public function test()
    {
        $channelManager = $this->mock(ChannelManager::class);
        $contextManager = $this->mock(ContextManager::class);
        $conversationManager = $this->mock(ConversationManager::class);
        $storyManager = $this->mock(StoryManager::class);
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $context = $this->mock(Context::class);
        $message = $this->mock(Message::class);
        $story = $this->mock(Story::class);

        $driver->shouldReceive('message')->andReturn($message);

        $channelManager->shouldReceive('driver')->with(request(), $channel)->andReturn($driver)->once();
        $driver->shouldReceive('verifyRequest')->once();

        $contextManager->shouldReceive('resolve')->with($driver)->andReturn($context)->once();

        $storyManager->shouldReceive('find')->with($context, $message)->andReturn($story)->once();

        $conversationManager->shouldReceive('start')->with($context, $driver, $channel, $story)->once();

        $bot = new Bot($channelManager, $contextManager, $conversationManager, $storyManager);
        $bot->process($channel);
    }

}