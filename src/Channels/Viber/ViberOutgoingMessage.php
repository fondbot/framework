<?php

declare(strict_types=1);

namespace FondBot\Channels\Viber;


use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Conversation\Keyboard;

class ViberOutgoingMessage implements OutgoingMessage
{

    /**
     * Get receiver.
     *
     * @return User
     */
    public function getRecipient(): User
    {
        // TODO: Implement getRecipient() method.
    }

    /**
     * Get message text.
     *
     * @return string
     */
    public function getText(): string
    {
        // TODO: Implement getText() method.
    }

    /**
     * Get keyboard.
     *
     * @return Keyboard|null
     */
    public function getKeyboard(): ?Keyboard
    {
        // TODO: Implement getKeyboard() method.
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}