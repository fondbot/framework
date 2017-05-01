<?php

declare(strict_types=1);

namespace FondBot\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Command;

class SendRequest implements Command
{
    public $chat;
    public $user;
    public $endpoint;
    public $parameters;

    public function __construct(Chat $chat, User $user, string $endpoint, array $parameters = [])
    {
        $this->chat = $chat;
        $this->user = $user;
        $this->endpoint = $endpoint;
        $this->parameters = $parameters;
    }
}
