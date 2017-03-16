<?php
declare(strict_types=1);

namespace FondBot\Listeners;

use FondBot\Contracts\Database\Services\MessageService;
use FondBot\Contracts\Events\MessageReceived;

class MessageReceivedListener
{

    /** @var \FondBot\Database\Services\MessageService */
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
