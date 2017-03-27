<?php

declare(strict_types=1);

namespace FondBot\Conversation\Fallback;

use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Conversation\Interaction;
use FondBot\Contracts\Conversation\Keyboard;

class FallbackInteraction extends Interaction
{
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
     * Message text to be sent to Participant.
     *
     * @return string
     */
    public function text(): string
    {
        return collect([
            'Sorry, I could not understand you.',
            'Oops, I can\'t do that 😔',
            'My developer did not teach to do that.',
        ])->random();
    }

    /**
     * Process received message.
     *
     * @param ReceivedMessage $message
     */
    public function process(ReceivedMessage $message): void
    {

    }
}
