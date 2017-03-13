<?php
declare(strict_types=1);

namespace FondBot\Jobs;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\ChannelManager;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\ConversationManager;
use FondBot\Conversation\StoryManager;
use FondBot\Database\Entities\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartConversation implements ShouldQueue
{

    use InteractsWithQueue, Queueable, SerializesModels;

    private $request;
    private $channel;

    public function __construct(Request $request, Channel $channel)
    {
        $this->request = $request;
        $this->channel = $channel;
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

        // Start Conversation
        $conversationManager->start($context, $driver, $this->channel, $story);
    }

}