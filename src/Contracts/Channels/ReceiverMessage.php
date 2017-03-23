<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Contracts\Conversation\Keyboard;

/**
 * Message to be sent to receiver.
 */
interface ReceiverMessage
{
    /**
     * Get receiver.
     *
     * @return Receiver
     */
    public function getReceiver(): Receiver;

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
