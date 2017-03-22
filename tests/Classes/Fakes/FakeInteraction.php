<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use FondBot\Conversation\Keyboard;
use FondBot\Conversation\Interaction;

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
     * Process reply.
     */
    protected function process(): void
    {
        $this->remember('key', 'value');
    }
}
