<?php
declare(strict_types=1);

namespace Tests\Classes;

use FondBot\Channels\Objects\Message;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\Keyboard;

class ExampleInteraction extends Interaction
{


    /**
     * Message to be sent to Participant
     *
     * @return Message
     */
    public function message(): Message
    {
        return Message::create('example');
    }

    /**
     * Keyboard to be shown to Participant
     *
     * @return Keyboard|null
     */
    public function keyboard(): ?Keyboard
    {
        return null;
    }

    /**
     * Process reply
     */
    protected function process(): void
    {
    }
}
