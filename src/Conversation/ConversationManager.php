<?php
declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Driver;
use FondBot\Database\Entities\Channel;
use FondBot\Database\Services\ParticipantService;
use FondBot\Traits\Loggable;

class ConversationManager
{
    use Loggable;

    private $contextManager;
    private $participantService;

    public function __construct(
        ContextManager $contextManager,
        ParticipantService $participantService
    ) {
        $this->contextManager = $contextManager;
        $this->participantService = $participantService;
    }

    /**
     * Start or continue conversation
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
        $participant = $driver->getParticipant();

        // Store Participant in database
        $this->participantService->createOrUpdate([
            'channel_id' => $channel->id,
            'identifier' => $participant->getIdentifier(),
            'name' => $participant->getName(),
            'username' => $participant->getUsername(),
        ], ['channel_id' => $channel->id, 'identifier' => $participant->getIdentifier()]);

        $context->setStory($story);
        $this->contextManager->save($context);

        $story->run($context);
    }
}
