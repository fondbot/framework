<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Drivers\Command;
use FondBot\Drivers\ReceivedMessage\Attachment;
use FondBot\Drivers\User;

class SendAttachment implements Command
{
    public $recipient;
    public $attachment;

    public function __construct(User $recipient, Attachment $attachment)
    {
        $this->recipient = $recipient;
        $this->attachment = $attachment;
    }
}
