<?php

declare(strict_types=1);

namespace FondBot\Contracts\Events;

use FondBot\Contracts\Database\Entities\Participant;

class MessageReceived
{
    private $participant;
    private $text;

    public function __construct(
        Participant $participant,
        string $text
    ) {
        $this->participant = $participant;
        $this->text = $text;
    }

    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
