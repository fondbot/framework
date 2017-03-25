<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\ReceivedMessage;

interface Interaction
{
    /**
     * Get current context instance.
     *
     * @return Context
     */
    public function getContext(): Context;

    /**
     * Set context.
     *
     * @param Context $context
     */
    public function setContext(Context $context): void;

    /**
     * Get message receiver.
     *
     * @return User
     */
    public function getSender(): User;

    /**
     * Get sender's message.
     *
     * @return ReceivedMessage
     */
    public function getSenderMessage(): ReceivedMessage;

    /**
     * Message text to be sent to Participant.
     *
     * @return string
     */
    public function text(): string;

    /**
     * Keyboard to be shown to Participant.
     *
     * @return Keyboard|null
     */
    public function keyboard(): ?Keyboard;

    /**
     * Run interaction.
     */
    public function run(): void;
}
