<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Conversation\Keyboard;
use FondBot\Drivers\Command;
use FondBot\Drivers\User;

class SendMessage implements Command
{
    public $recipient;
    public $text;
    public $keyboard;

    public function __construct(User $recipient, string $text, Keyboard $keyboard = null)
    {
        $this->recipient = $recipient;
        $this->text = $text;
        $this->keyboard = $keyboard;
    }
}
