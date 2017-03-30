<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Contracts\Conversation\Keyboard;

/**
 * Message to be sent to receiver.
 */
interface OutgoingMessage
{
    /**
     * Get receiver.
     *
     * @return User
     */
    public function getRecipient(): User;

    /**
     * Get message text.
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Get keyboard.
     *
     * @return Keyboard|null
     */
    public function getKeyboard(): ?Keyboard;

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array;
}
