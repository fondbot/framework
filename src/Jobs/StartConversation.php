<?php

declare(strict_types=1);

namespace FondBot\Jobs;

use FondBot\Channels\Driver;
use Illuminate\Bus\Queueable;
use FondBot\Channels\ChannelManager;
use FondBot\Conversation\StoryManager;
use FondBot\Database\Entities\Channel;
use Illuminate\Queue\SerializesModels;
use FondBot\Conversation\ContextManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use FondBot\Conversation\ConversationManager;

class StartConversation implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $channel;
    private $request;

    public function __construct(Channel $channel, array $request)
    {
        $this->channel = $channel;
        $this->request = $request;
    }

    public function handle(
        ChannelManager $channelManager,
        ContextManager $contextManager,
        StoryManager $storyManager,
        ConversationManager $conversationManager
    ) {
        /** @var Driver $driver */
        $driver = $channelManager->createDriver($this->request, $this->channel);

        // Resolve context
        $context = $contextManager->resolve($driver);

        // Find story
        $story = $storyManager->find($context, $driver->getMessage());

        // No story found
        if ($story === null) {
            return;
        }

        // Start Conversation
        $conversationManager->start($context, $driver, $this->channel, $story);
    }
}
