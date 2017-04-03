<?php

declare(strict_types=1);

namespace FondBot\Channels\Telegram;

use FondBot\Contracts\Channels\User;
use FondBot\Conversation\Buttons\UrlButton;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Conversation\Buttons\PayloadButton;
use FondBot\Channels\Telegram\Buttons\RequestContactButton;

class TelegramOutgoingMessage implements OutgoingMessage
{
    private const KEYBOARD_REPLY = 'keyboard';
    private const KEYBOARD_INLINE = 'inline_keyboard';

    private $recipient;
    private $text;
    private $keyboard;

    public function __construct(User $recipient, string $text, Keyboard $keyboard = null)
    {
        $this->recipient = $recipient;
        $this->text = $text;
        $this->keyboard = $keyboard;
    }

    /**
     * Get recipient.
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
            'chat_id' => $this->recipient->getId(),
            'text' => $this->text,
        ];

        if ($replyMarkup = $this->getReplyMarkup()) {
            $payload['reply_markup'] = json_encode($this->getReplyMarkup());
        }

        return $payload;
    }

    private function getReplyMarkup(): ?array
    {
        if ($this->keyboard !== null) {
            $type = $this->detectKeyboardType();

            $keyboard = [];
            switch ($type) {
                case self::KEYBOARD_REPLY:
                    $keyboard = $this->compileReplyKeyboard();
                    break;
                case self::KEYBOARD_INLINE:
                    $keyboard = $this->compileInlineKeyboard();
                    break;
            }

            return $keyboard;
        }

        return null;
    }

    /**
     * Compile reply keyboard markup.
     *
     * @return array
     */
    private function compileReplyKeyboard(): array
    {
        $buttons = [];
        foreach ($this->keyboard->getButtons() as $button) {
            $parameters = ['text' => $button->getLabel()];

            if ($button instanceof RequestContactButton) {
                $parameters['request_contact'] = true;
            }

            $buttons[] = $parameters;
        }

        return [
            'keyboard' => [$buttons],
            'one_time_keyboard' => true,
        ];
    }

    /**
     * Compile inline keyboard markup.
     *
     * @return array
     */
    private function compileInlineKeyboard(): array
    {
        $buttons = [];
        foreach ($this->keyboard->getButtons() as $button) {
            $parameters = ['text' => $button->getLabel()];

            if ($button instanceof UrlButton) {
                $parameters['url'] = $button->getUrl();
            } elseif ($button instanceof PayloadButton) {
                $parameters['callback_data'] = $button->getPayload();
            }

            $buttons[] = $parameters;
        }

        return [
            'inline_keyboard' => [$buttons],
        ];
    }

    private function detectKeyboardType(): string
    {
        $button = collect($this->keyboard->getButtons())->first();

        if ($button instanceof PayloadButton || $button instanceof UrlButton) {
            return self::KEYBOARD_INLINE;
        }

        return self::KEYBOARD_REPLY;
    }
}
