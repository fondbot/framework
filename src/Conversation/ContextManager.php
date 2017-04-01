<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Bot;
use FondBot\Traits\Loggable;
use FondBot\Contracts\Cache\Cache;
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

        $intent = $value['intent'] !== null ? Bot::getInstance()->get($value['intent']) : null;
        $interaction = $value['interaction'] !== null ? Bot::getInstance()->get($value['interaction']) : null;

        return new Context(
            $channel,
            $sender,
            $message,
            $intent,
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
