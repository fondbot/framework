<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\ReceiverMessage;

class FakeReceiverMessage implements ReceiverMessage
{
    private $recipient;
    private $text;
    private $keyboard;

    public function __construct(Sender $recipient, $text, Keyboard $keyboard = null)
    {
        $this->recipient = $recipient;
        $this->text = $text;
        $this->keyboard = $keyboard;
    }

    /**
     * Get receiver.
     *
     * @return Sender
     */
    public function getRecipient(): Sender
    {
        return $this->recipient;
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
            'receiver' => $this->recipient->getId(),
            'text' => $this->text,
            'keyboard' => $this->keyboard->getType(),
        ];
    }
}
