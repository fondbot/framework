<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;

trait InteractsWithContext
{
    /**
     * Get channel.
     *
     * @return Channel
     */
    protected function getChannel(): Channel
    {
        return $this->context()->getChannel();
    }

    /**
     * Get chat.
     *
     * @return Chat
     */
    protected function getChat(): Chat
    {
        return $this->context()->getChat();
    }

    /**
     * Get user.
     *
     * @return User
     */
    protected function getUser(): User
    {
        return $this->context()->getUser();
    }

    /**
     * Get the whole context or a single value.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return Context|mixed
     */
    protected function context(string $key = null, $default = null)
    {
        return context($key, $default);
    }

    /**
     * Remember value in context.
     *
     * @param string $key
     * @param mixed  $value
     */
    protected function remember(string $key, $value): void
    {
        context()->set($key, $value);
    }
}
