<?php

declare(strict_types=1);

namespace FondBot\Conversation\Fallback;

use FondBot\Conversation\Interaction;
use FondBot\Conversation\Keyboard;
use FondBot\Nifty\Emoji;

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
            'Oops, I can\'t do that '.Emoji::pensiveFace(),
            'My developer did not teach to do that.',
        ])->random();
    }

    /**
     * Process reply.
     */
    protected function process(): void
    {
    }
}
