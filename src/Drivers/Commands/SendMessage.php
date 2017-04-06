<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Drivers\User;
use FondBot\Drivers\Command;
use FondBot\Conversation\Keyboard;

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
