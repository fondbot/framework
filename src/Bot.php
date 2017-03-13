<?php
declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\ChannelManager;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\ConversationManager;
use FondBot\Conversation\StoryManager;
use FondBot\Database\Entities\Channel;
use FondBot\Traits\Loggable;

class Bot
{

    use Loggable;

    private $channelManager;
    private $contextManager;
    private $conversationManager;
    private $storyManager;

    public function __construct(
        ChannelManager $channelManager,
        ContextManager $contextManager,
        ConversationManager $conversationManager,
        StoryManager $storyManager
    ) {
        $this->channelManager = $channelManager;
        $this->contextManager = $contextManager;
        $this->conversationManager = $conversationManager;
        $this->storyManager = $storyManager;
    }

    public function process(Channel $channel): void
    {
        $request = request();

        /** @var Driver $driver */
        $driver = $this->channelManager->createDriver($request, $channel);

        // Verify request
        $driver->verifyRequest();

        // Resolve context
        $context = $this->contextManager->resolve($driver);

        // Find story
        $story = $this->storyManager->find($context, $driver->getMessage());

        // Start context
        $this->conversationManager->start($context, $driver, $channel, $story);
    }

}