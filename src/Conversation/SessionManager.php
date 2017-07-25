<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Channels\Channel;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Contracts\Container\Container;

class SessionManager
{
    private $container;
    private $cache;

    public function __construct(Container $container, Store $cache)
    {
        $this->container = $container;
        $this->cache = $cache;
    }

    /**
     * Load session.
     *
     * @param Channel $channel
     * @param Driver  $driver
     *
     * @return Session
     */
    public function load(Channel $channel, Driver $driver): Session
    {
        $chat = $driver->getChat();
        $user = $driver->getUser();
        $message = $driver->getMessage();
        $key = $this->key($channel, $chat, $user);
        $value = $this->cache->get($key);

        $intent = $value['intent'] !== null ? $this->container->make($value['intent']) : null;
        $interaction = $value['interaction'] !== null ? $this->container->make($value['interaction']) : null;

        return new Session($channel, $chat, $user, $message, $intent, $interaction);
    }

    /**
     * Save session.
     *
     * @param Session $session
     */
    public function save(Session $session): void
    {
        $key = $this->key($session->getChannel(), $session->getChat(), $session->getUser());

        $this->cache->forever($key, $session->toArray());
    }

    /**
     * Close session.
     *
     * @param Session $session
     */
    public function close(Session $session): void
    {
        $key = $this->key($session->getChannel(), $session->getChat(), $session->getUser());

        $this->cache->forget($key);
    }

    /**
     * Get key of session.
     *
     * @param Channel $channel
     * @param Chat    $chat
     * @param User    $user
     *
     * @return string
     */
    private function key(Channel $channel, Chat $chat, User $user): string
    {
        return 'session.'.$channel->getName().'.'.$chat->getId().'.'.$user->getId();
    }
}
