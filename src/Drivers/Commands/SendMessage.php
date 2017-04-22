<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Command;
use FondBot\Conversation\Template;

class SendMessage implements Command
{
    public $chat;
    public $recipient;
    public $text;
    public $template;

    public function __construct(Chat $chat, User $recipient, string $text, Template $template = null)
    {
        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->text = $text;
        $this->template = $template;
    }
}
