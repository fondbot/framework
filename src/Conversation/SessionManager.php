<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Contracts\Cache;
use FondBot\Channels\Channel;
use Psr\Container\ContainerInterface;

class SessionManager
{
    private $container;
    private $cache;

    public function __construct(ContainerInterface $container, Cache $cache)
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
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function load(Channel $channel, Driver $driver): Session
    {
        $chat = $driver->getChat();
        $sender = $driver->getUser();
        $message = $driver->getMessage();
        $key = $this->key($channel, $chat, $sender);
        $value = $this->cache->get($key);

        $intent = $value['intent'] !== null ? $this->container->get($value['intent']) : null;
        $interaction = $value['interaction'] !== null ? $this->container->get($value['interaction']) : null;

        return new Session($channel, $chat, $sender, $message, $intent, $interaction, $value['values'] ?? []);
    }

    /**
     * Save session.
     *
     * @param Session $session
     */
    public function save(Session $session): void
    {
        $key = $this->key($session->getChannel(), $session->getChat(), $session->getUser());

        $this->cache->store($key, $session->toArray());
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
     * @param Chat   $chat
     * @param User   $user
     *
     * @return string
     */
    private function key(Channel $channel, Chat $chat, User $user): string
    {
        return 'session.'.$channel->getName().'.'.$chat->getId().'.'.$user->getId();
    }
}
