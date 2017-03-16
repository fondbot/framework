<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Driver;
use FondBot\Traits\Loggable;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Services\ParticipantService as ParticipantServiceContract;

class ConversationManager
{
    use Loggable;

    private $contextManager;
    private $participantService;

    public function __construct(
        ContextManager $contextManager,
        ParticipantServiceContract $participantService
    ) {
        $this->contextManager = $contextManager;
        $this->participantService = $participantService;
    }

    /**
     * Start or continue conversation.
     *
     * @param Context $context
     * @param Driver $driver
     * @param Channel $channel
     * @param Story $story
     */
    public function start(
        Context $context,
        Driver $driver,
        Channel $channel,
        Story $story
    ): void {
        $sender = $driver->getSender();

        // Store sender in database as participant
        $this->participantService->createOrUpdate([
            'channel_id' => $channel->id,
            'identifier' => $sender->getIdentifier(),
            'name' => $sender->getName(),
            'username' => $sender->getUsername(),
        ], ['channel_id' => $channel->id, 'identifier' => $sender->getIdentifier()]);

        $context->setStory($story);
        $this->contextManager->save($context);

        $story->run($context);
    }
}
