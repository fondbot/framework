<?php

declare(strict_types=1);

namespace FondBot\Listeners;

use FondBot\Contracts\Events\MessageSent;
use FondBot\Contracts\Database\Services\MessageService;
use FondBot\Contracts\Database\Services\ParticipantService;

class MessageSentListener
{
    private $participantService;
    private $messageService;

    public function __construct(
        ParticipantService $participantService,
        MessageService $messageService
    ) {
        $this->participantService = $participantService;
        $this->messageService = $messageService;
    }

    public function handle(MessageSent $event)
    {
        $participant = $this->participantService->findByChannelAndIdentifier(
            $event->getContext()->getDriver()->getChannel(),
            $event->getReceiver()->getIdentifier()
        );

        $this->messageService->create([
            'receiver_id' => $participant->id,
            'text' => $event->getText(),
            'parameters' => [],
        ]);
    }
}
