<?php

declare(strict_types=1);

namespace FondBot\Listeners;

use FondBot\Contracts\Events\MessageReceived;
use FondBot\Contracts\Database\Services\MessageService;

class MessageReceivedListener
{
    private $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function handle(MessageReceived $event)
    {
        $participant = $event->getParticipant();
        $message = $event->getMessage();

        $location = $message->getLocation() !== null ? $message->getLocation()->toArray() : null;
        $attachment = $message->getAttachment() !== null ? $message->getAttachment()->toArray() : null;

        $this->messageService->create([
            'sender_id' => $participant->id,
            'text' => $message->getText(),
            'location' => $location,
            'attachment' => $attachment,
        ]);
    }
}
