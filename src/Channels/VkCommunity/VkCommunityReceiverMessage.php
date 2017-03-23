<?php

declare(strict_types=1);

namespace FondBot\Channels\VkCommunity;

use FondBot\Contracts\Channels\Receiver;
use FondBot\Contracts\Channels\ReceiverMessage;
use FondBot\Contracts\Conversation\Keyboard;

class VkCommunityReceiverMessage implements ReceiverMessage
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
            'message' => $this->text,
            'user_id' => $this->receiver->getIdentifier(),
        ];
    }
}
