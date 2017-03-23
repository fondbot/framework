<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use FondBot\Contracts\Channels\Receiver;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\ReceiverMessage;

class FakeReceiverMessage implements ReceiverMessage
{
    private $receiver;
    private $text;
    private $keyboard;

    public function __construct(Receiver $receiver, $text, Keyboard $keyboard = null)
    {
        $this->receiver = $receiver;
        $this->text = $text;
        $this->keyboard = $keyboard;
    }

    /**
     * Get receiver.
     *
     * @return Receiver
     */
    public function getReceiver(): Receiver
    {
        return $this->receiver;
    }

    /**
     * Get message text.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Get keyboard.
     *
     * @return Keyboard|null
     */
    public function getKeyboard(): ?Keyboard
    {
        return $this->keyboard;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'receiver' => $this->receiver->getIdentifier(),
            'text' => $this->text,
            'keyboard' => $this->keyboard->getType(),
        ];
    }
}
