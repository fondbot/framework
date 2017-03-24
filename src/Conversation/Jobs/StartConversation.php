<?php

declare(strict_types=1);

namespace FondBot\Conversation\Jobs;

use FondBot\Traits\Loggable;
use Illuminate\Bus\Queueable;
use FondBot\Channels\ChannelManager;
use FondBot\Conversation\StoryManager;
use Illuminate\Queue\SerializesModels;
use FondBot\Conversation\ContextManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use FondBot\Conversation\ConversationManager;
use FondBot\Contracts\Database\Entities\Channel;

class StartConversation implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, Loggable;

    private $channel;
    private $request;
    private $headers;

    public function __construct(Channel $channel, array $request, array $headers)
    {
        $this->channel = $channel;
        $this->request = $request;
        $this->headers = $headers;
    }

    public function handle(
        ChannelManager $channelManager,
        ContextManager $contextManager,
        StoryManager $storyManager,
        ConversationManager $conversationManager
    ) {
        $this->debug('handle', ['channel' => $this->channel->toArray(), 'request' => $this->request]);

        $driver = $channelManager->createDriver($this->channel, $this->request, $this->headers);

        // Dispatch job to store message
        dispatch((new StoreMessage($this->channel, $driver->getSender(), $driver->getMessage()))->onQueue('fondbot'));

        // Resolve context
        $context = $contextManager->resolve($this->channel, $driver);

        // Find story
        $story = $storyManager->find($context, $driver->getMessage());

        // No story found
        if ($story === null) {
            return;
        }

        // Start Conversation
        $conversationManager->start($driver, $context, $story);
    }
}
