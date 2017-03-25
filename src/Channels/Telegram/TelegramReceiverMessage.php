<?php

declare(strict_types=1);

namespace FondBot\Channels\Telegram;

use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\ReceiverMessage;

class TelegramReceiverMessage implements ReceiverMessage
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
     * Get recipient.
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
            'chat_id' => $this->recipient->getId(),
            'text' => $this->text,
            'reply_markup' => json_encode($this->getReplyMarkup()),
        ];
    }

    private function getReplyMarkup(): ?array
    {
        if ($this->keyboard !== null) {
            $buttons = [];

            foreach ($this->keyboard->getButtons() as $button) {
                $buttons[] = ['text' => $button->getLabel()];
            }

            return [
                'keyboard' => [$buttons],
                'resize_keyboard' => true,
            ];
        }

        return null;
    }
}
