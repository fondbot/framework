<?php
declare(strict_types=1);

namespace FondBot\Listeners;

use FondBot\Contracts\Database\Services\ChannelService;
use FondBot\Contracts\Database\Services\MessageService;
use FondBot\Contracts\Database\Services\ParticipantService;
use FondBot\Contracts\Events\MessageSent;

class MessageSentListener
{

    /** @var \FondBot\Database\Services\ChannelService */
    private $channelService;

    /** @var \FondBot\Database\Services\ParticipantService */
    private $participantService;

    /** @var \FondBot\Database\Services\MessageService */
    private $messageService;

    public function __construct(
        ChannelService $channelService,
        ParticipantService $participantService,
        MessageService $messageService
    ) {
        $this->channelService = $channelService;
        $this->participantService = $participantService;
        $this->messageService = $messageService;
    }

    public function handle(MessageSent $event)
    {
        $channel = $this->channelService->findByName(
            $event->getContext()->getDriver()->getChannelName()
        );
        $participant = $this->participantService->findByChannelAndIdentifier(
            $channel,
            $event->getReceiver()->getIdentifier()
        );

        $this->messageService->create([
            'receiver_id' => $participant->id,
            'text' => $event->getText(),
            'parameters' => [],
        ]);
    }
}
