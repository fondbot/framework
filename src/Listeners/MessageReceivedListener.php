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
        $this->messageService->create([
            'sender_id' => $event->getParticipant()->id,
            'text' => $event->getText(),
            'parameters' => [],
        ]);
    }
}
