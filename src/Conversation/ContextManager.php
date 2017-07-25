<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Channels\Channel;
use Illuminate\Contracts\Cache\Store;

class ContextManager
{
    private $cache;

    public function __construct(Store $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Load context.
     *
     * @param Channel $channel
     * @param Driver  $driver
     *
     * @return Context
     */
    public function load(Channel $channel, Driver $driver): Context
    {
        $chat = $driver->getChat();
        $user = $driver->getUser();
        $key = $this->key($channel, $chat, $user);

        $value = $this->cache->get($key, []);

        return new Context($channel, $value['chat'], $value['user'], $value['items']);
    }

    /**
     * Save context.
     *
     * @param Context $context
     */
    public function save(Context $context): void
    {
        $key = $this->key($context->getChannel(), $context->getChat(), $context->getUser());

        $this->cache->forever($key, $context->toArray());
    }

    /**
     * Clear context.
     *
     * @param Context $context
     */
    public function clear(Context $context): void
    {
        $key = $this->key($context->getChannel(), $context->getChat(), $context->getUser());

        $this->cache->forget($key);
    }

    /**
     * Get key of context.
     *
     * @param Channel $channel
     * @param Chat    $chat
     * @param User    $user
     *
     * @return string
     */
    private function key(Channel $channel, Chat $chat, User $user): string
    {
        return 'context.'.$channel->getName().'.'.$chat->getId().'.'.$user->getId();
    }
}
