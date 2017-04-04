<?php
declare(strict_types = 1);

namespace FondBot\Channels\Slack;

use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\OutgoingMessage;

class SlackOutgoingMessage implements OutgoingMessage
{
    private $recipient;
    private $text;
    private $keyboard;

    public function __construct(User $recipient, $text, Keyboard $keyboard = null)
    {
        $this->recipient = $recipient;
        $this->text      = $text;
        $this->keyboard  = $keyboard;
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
        return [
            'channel' => $this->recipient->getId(),
            'text'    => $this->text
        ];
    }

}