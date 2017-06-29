<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Command;
use FondBot\Templates\Attachment;

class SendAttachment implements Command
{
    private $chat;
    private $recipient;
    private $attachment;

    public function __construct(Chat $chat, User $recipient, Attachment $attachment)
    {
        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->attachment = $attachment;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'SendAttachment';
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getAttachment(): Attachment
    {
        return $this->attachment;
    }
}
