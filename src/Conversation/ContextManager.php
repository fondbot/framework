<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Cache\Cache;
use FondBot\Traits\Loggable;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\Driver;

class ContextManager
{
    use Loggable;

    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Resolve context instance.
     *
     * @param string $channel
     * @param Driver $driver
     *
     * @return Context
     */
    public function resolve(string $channel, Driver $driver): Context
    {
        $this->debug('resolve', ['driver' => get_class($driver)]);

        $sender = $driver->getUser();
        $message = $driver->getMessage();
        $key = $this->key($channel, $sender);
        $value = $this->cache->get($key);

        $story = $value['story'] !== null ? resolve($value['story']) : null;
        $interaction = $value['interaction'] !== null ? resolve($value['interaction']) : null;

        return new Context(
            $channel,
            $sender,
            $message,
            $story,
            $interaction,
            $value['values'] ?? []
        );
    }

    /**
     * Save updated context.
     *
     * @param Context $context
     */
    public function save(Context $context): void
    {
        $this->debug('save', ['context' => $context]);

        $key = $this->key($context->getChannel(), $context->getUser());

        $this->cache->store($key, $context->toArray());
    }

    /**
     * Clear context.
     *
     * @param Context $context
     */
    public function clear(Context $context): void
    {
        $this->debug('clear', ['context' => $context]);

        $key = $this->key($context->getChannel(), $context->getUser());

        $this->cache->forget($key);
    }

    /**
     * Get key of current context in storage (Cache, Memory, etc.).
     *
     * @param string $channel
     * @param User   $sender
     *
     * @return string
     */
    private function key(string $channel, User $sender): string
    {
        return 'context.'.$channel.'.'.$sender->getId();
    }
}
