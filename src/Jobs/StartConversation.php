<?php

declare(strict_types=1);

namespace FondBot\Jobs;

use FondBot\Traits\Loggable;
use Illuminate\Bus\Queueable;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\Driver;
use FondBot\Conversation\StoryManager;
use Illuminate\Queue\SerializesModels;
use FondBot\Conversation\ContextManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use FondBot\Contracts\Events\MessageReceived;
use FondBot\Conversation\ConversationManager;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Services\ParticipantService;

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
        ConversationManager $conversationManager,
        ParticipantService $participantService
    ) {
        $this->debug('handle', ['channel' => $this->channel->toArray(), 'request' => $this->request]);

        /** @var Driver $driver */
        $driver = $channelManager->createDriver($this->request, $this->headers, $this->channel);

        // Store sender in database as participant
        $participant = $participantService->createOrUpdate([
            'channel_id' => $this->channel->id,
            'identifier' => $driver->getSender()->getIdentifier(),
            'name' => $driver->getSender()->getName(),
            'username' => $driver->getSender()->getUsername(),
        ], ['channel_id' => $this->channel->id, 'identifier' => $driver->getSender()->getIdentifier()]);

        // Resolve context
        $context = $contextManager->resolve($driver);

        // Fire an event that message was received
        $this->events()->dispatch(
            new MessageReceived(
                $participant,
                $driver->getMessage()
            )
        );

        // Find story
        $story = $storyManager->find($context, $driver->getMessage());

        // No story found
        if ($story === null) {
            return;
        }

        // Start Conversation
        $conversationManager->start($context, $story);
    }

    private function events(): Dispatcher
    {
        return resolve(Dispatcher::class);
    }
}
