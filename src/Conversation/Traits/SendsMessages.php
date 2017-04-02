<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Bot;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Conversation\Keyboard;

trait SendsMessages
{
    /** @var Bot */
    protected $bot;

    /**
     * Get user.
     *
     * @return User
     */
    abstract protected function user(): User;

    /**
     * Send reply to user.
     *
     * @param string        $text
     * @param Keyboard|null $keyboard
     */
    public function sendMessage(string $text, Keyboard $keyboard = null): void
    {
        $this->bot->sendMessage($this->user(), $text, $keyboard);
    }
}
