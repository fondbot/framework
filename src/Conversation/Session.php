<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Channels\Channel;
use FondBot\Contracts\Arrayable;
use FondBot\Drivers\ReceivedMessage;

class Session implements Arrayable
{
    private $channel;
    private $chat;
    private $user;
    private $message;
    private $intent;
    private $interaction;

    public function __construct(
        Channel $channel,
        Chat $chat,
        User $user,
        ReceivedMessage $message,
        Intent $intent = null,
        Interaction $interaction = null
    ) {
        $this->channel = $channel;
        $this->chat = $chat;
        $this->user = $user;
        $this->message = $message;
        $this->intent = $intent;
        $this->interaction = $interaction;
    }

    /**
     * Get channel name.
     *
     * @return Channel
     */
    public function getChannel(): Channel
    {
        return $this->channel;
    }

    /**
     * Get chat.
     *
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Get message received from user.
     *
     * @return ReceivedMessage
     */
    public function getMessage(): ReceivedMessage
    {
        return $this->message;
    }

    /**
     * Get current intent.
     *
     * @return Intent|Conversable|null
     */
    public function getIntent(): ?Intent
    {
        return $this->intent;
    }

    /**
     * Set intent.
     *
     * @param Intent $intent
     */
    public function setIntent(Intent $intent): void
    {
        $this->intent = $intent;
    }

    /**
     * Get interaction.
     *
     * @return Interaction|Conversable|null
     */
    public function getInteraction(): ?Interaction
    {
        return $this->interaction;
    }

    /**
     * Set interaction.
     *
     * @param Interaction|null $interaction
     */
    public function setInteraction(?Interaction $interaction): void
    {
        $this->interaction = $interaction;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'intent' => $this->intent !== null ? get_class($this->intent) : null,
            'interaction' => $this->interaction !== null ? get_class($this->interaction) : null,
        ];
    }
}
