<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Drivers\User;
use FondBot\Drivers\Command;
use FondBot\Drivers\ReceivedMessage\Attachment;

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
