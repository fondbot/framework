<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use FondBot\Conversation\Session;
use FondBot\Conversation\SessionManager;

class LoadSession
{
    private $channel;
    private $chat;
    private $user;

    public function __construct(Channel $channel, Chat $chat, User $user)
    {
        $this->channel = $channel;
        $this->chat = $chat;
        $this->user = $user;
    }

    public function handle(SessionManager $manager): Session
    {
        return $manager->load($this->channel, $this->chat, $this->user);
    }
}
