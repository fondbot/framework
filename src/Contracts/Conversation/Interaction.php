<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\ReceivedMessage;

interface Interaction
{
    /**
     * Get message receiver.
     *
     * @return User
     */
    public function getUser(): User;

    /**
     * Message text to be sent to Participant.
     *
     * @return string
     */
    public function text(): string;

    /**
     * Keyboard to be shown to Participant.
     *
     * @return Keyboard|null
     */
    public function keyboard(): ?Keyboard;

    /**
     * Process received message.
     *
     * @param ReceivedMessage $message
     */
    public function process(ReceivedMessage $message): void;
}
