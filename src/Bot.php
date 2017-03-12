<?php
declare(strict_types = 1);

namespace FondBot;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\ChannelManager;
use FondBot\Conversation\Context;
use FondBot\Conversation\ConversationManager;
use FondBot\Conversation\StoryManager;
use FondBot\Database\Entities\Channel;
use FondBot\Traits\Loggable;
use Illuminate\Http\Request;

class Bot
{
    use Loggable;

    private $channelManager;
    private $conversationManager;
    private $storyManager;

    public function __construct(
        ChannelManager $channelManager,
        ConversationManager $conversationManager,
        StoryManager $storyManager
    ) {
        $this->channelManager = $channelManager;
        $this->conversationManager = $conversationManager;
        $this->storyManager = $storyManager;
    }

    public function process(Request $request, Channel $channel)
    {
        /** @var Driver $driver */
        $driver = $this->channelManager->driver($request, $channel);

        // Verify request
        if ($driver->isInvalidRequest()) {
            return $driver->handleInvalidRequest();
        }

        // Resolve context
        $context = Context::instance($driver);

        // Find story
        $story = $this->storyManager->find($context, $driver->message());

        // Start context
        $this->conversationManager->start($context, $driver, $channel, $story);

        return 'OK';
    }
}