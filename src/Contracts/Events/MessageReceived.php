<?php

declare(strict_types=1);

namespace FondBot\Contracts\Events;

use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Contracts\Database\Entities\Participant;

class MessageReceived
{
    private $participant;
    private $message;

    public function __construct(
        Participant $participant,
        SenderMessage $message
    ) {
        $this->participant = $participant;
        $this->message = $message;
    }

    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    public function getMessage(): SenderMessage
    {
        return $this->message;
    }
}
