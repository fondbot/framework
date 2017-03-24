<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Traits\Loggable;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Sender;
use FondBot\Contracts\Database\Entities\Channel;
use Illuminate\Contracts\Cache\Repository as Cache;

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
     * @param Channel $channel
     * @param Driver  $driver
     *
     * @return \FondBot\Conversation\Context
     */
    public function resolve(Channel $channel, Driver $driver): Context
    {
        $this->debug('resolve', ['driver' => get_class($driver)]);

        $sender = $driver->getSender();
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

        $key = $this->key($context->getChannel(), $context->getSender());

        $this->cache->forever($key, $context->toArray());
    }

    /**
     * Clear context.
     *
     * @param Context $context
     */
    public function clear(Context $context): void
    {
        $this->debug('clear', ['context' => $context]);

        $key = $this->key($context->getChannel(), $context->getSender());

        $this->cache->forget($key);
    }

    /**
     * Get key of current context in storage (Cache, Memory, etc.).
     *
     * @param Channel $channel
     * @param Sender  $sender
     *
     * @return string
     */
    private function key(Channel $channel, Sender $sender): string
    {
        return 'context.'.$channel->name.'.'.$sender->getIdentifier();
    }
}
