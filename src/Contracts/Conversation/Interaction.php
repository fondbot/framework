<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

use FondBot\Contracts\Channels\Sender;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\SenderMessage;

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
     * @return Sender
     */
    public function getSender(): Sender;

    /**
     * Get sender's message.
     *
     * @return SenderMessage
     */
    public function getSenderMessage(): SenderMessage;

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
