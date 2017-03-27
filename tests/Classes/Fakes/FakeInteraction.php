<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Conversation\Interaction;
use FondBot\Contracts\Conversation\Keyboard;

class FakeInteraction extends Interaction
{
    /**
     * Message text to be sent to Participant.
     *
     * @return string
     */
    public function text(): string
    {
        return 'example';
    }

    /**
     * Keyboard to be shown to Participant.
     *
     * @return Keyboard|null
     */
    public function keyboard(): ?Keyboard
    {
        return null;
    }

    /**
     * Process received message.
     *
     * @param ReceivedMessage $message
     */
    public function process(ReceivedMessage $message): void
    {
        $this->remember('key', 'value');
    }
}
