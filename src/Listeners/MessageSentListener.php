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
        $message = $event->getMessage();

        $participant = $this->participantService->findByChannelAndIdentifier(
            $event->getContext()->getChannel(),
            $message->getReceiver()->getIdentifier()
        );

        $this->messageService->create([
            'receiver_id' => $participant->id,
            'text' => $message->getText(),
        ]);
    }
}
