<?php

declare(strict_types=1);

namespace FondBot\Channels\Facebook;

use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Conversation\Keyboards\BasicKeyboard;

class FacebookOutgoingMessage implements OutgoingMessage
{
    private $recipient;
    private $text;
    private $keyboard;

    public function __construct(User $recipient, $text, Keyboard $keyboard = null)
    {
        $this->recipient = $recipient;
        $this->text = $text;
        $this->keyboard = $keyboard;
    }

    /**
     * Get receiver.
     *
     * @return User
     */
    public function getRecipient(): User
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
        $payload = [
            'recipient' => [
                'id' => $this->recipient->getId(),
            ],
            'message' => [
                'text' => $this->text,
            ],
        ];

        if ($this->keyboard instanceof BasicKeyboard) {
            $payload['message']['quick_replies'] = $this->compileBasicKeyboard();
        }

        return $payload;
    }

    private function compileBasicKeyboard(): array
    {
        $payload = [];
        foreach ($this->keyboard->getButtons() as $button) {
            $payload[] = [
                'content_type' => 'text',
                'title' => $button->getLabel(),
                'payload' => $button->getLabel(),
            ];
        }

        return $payload;
    }
}
