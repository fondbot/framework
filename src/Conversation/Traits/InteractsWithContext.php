<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Bot;
use FondBot\Contracts\Drivers\User;

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
     * Get user.
     *
     * @return User
     */
    protected function user(): User
    {
        return $this->bot->getContext()->getUser();
    }
}
