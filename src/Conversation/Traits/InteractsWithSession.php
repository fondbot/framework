<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Helpers\Arr;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Application\Kernel;

trait InteractsWithSession
{
    /** @var Kernel */
    protected $kernel;

    /**
     * Get the whole context or a single value.
     *
     * @param string|null $key
     *
     * @return array|mixed
     */
    protected function context(string $key = null)
    {
        $context = $this->kernel->getSession()->getContext();
        if ($key === null) {
            return $context;
        }

        return Arr::get($context, $key);
    }

    /**
     * Remember value in context.
     *
     * @param string $key
     * @param        $value
     */
    protected function remember(string $key, $value): void
    {
        $this->kernel->getSession()->setContextValue($key, $value);
    }

    /**
     * Get chat.
     *
     * @return Chat
     */
    protected function getChat(): Chat
    {
        return $this->kernel->getSession()->getChat();
    }

    /**
     * Get user.
     *
     * @return User
     */
    protected function getUser(): User
    {
        return $this->kernel->getSession()->getUser();
    }
}
