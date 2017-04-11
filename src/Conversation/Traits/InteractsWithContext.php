<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Bot;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;

trait InteractsWithContext
{
    /** @var Bot */
    protected $bot;

    /**
     * Remember value in context.
     *
     * @param string $key
     * @param        $value
     */
    protected function remember(string $key, $value): void
    {
        $this->bot->getContext()->setValue($key, $value);
    }

    /**
     * Get chat.
     *
     * @return Chat
     */
    protected function getChat(): Chat
    {
        return $this->bot->getContext()->getChat();
    }

    /**
     * Get user.
     *
     * @return User
     */
    protected function getUser(): User
    {
        return $this->bot->getContext()->getUser();
    }
}
