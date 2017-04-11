<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Command;
use FondBot\Drivers\ReceivedMessage\Attachment;

class SendAttachment implements Command
{
    public $chat;
    public $recipient;
    public $attachment;

    public function __construct(Chat $chat, User $recipient, Attachment $attachment)
    {
        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->attachment = $attachment;
    }
}
