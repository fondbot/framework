<?php declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Database\Entities\Channel;
use FondBot\Database\Services\ParticipantService;
use FondBot\Traits\Loggable;

class ConversationManager
{

    use Loggable;

    private $participantService;

    public function __construct(ParticipantService $participantService)
    {
        $this->participantService = $participantService;
    }

    public function start(
        Context $context,
        Driver $driver,
        Channel $channel,
        ?Story $story
    ): void {
        $participant = $driver->participant();

        // Store Participant in database
        $this->participantService->createOrUpdate([
            'channel_id' => $channel->id,
            'identifier' => $participant->getIdentifier(),
            'name' => $participant->getName(),
            'username' => $participant->getUsername(),
        ], ['channel_id' => $channel->id, $participant]);

        // No story found
        if ($story === null) {
            return;
        }

        $context->setStory($story);
        $context->save();

        $story->run($context);
    }

}