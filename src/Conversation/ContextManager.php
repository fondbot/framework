<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Driver;
use Illuminate\Contracts\Cache\Repository as Cache;

class ContextManager
{
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Resolve context instance.
     *
     * @param Driver $driver
     *
     * @return Context
     */
    public function resolve(Driver $driver): Context
    {
        $key = $this->key($driver);

        $value = $this->cache->get($key);

        $story = $value['story'] !== null ? resolve($value['story']) : null;
        $interaction = $value['interaction'] !== null ? resolve($value['interaction']) : null;

        return new Context(
            $driver,
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
        $key = $this->key($context->getDriver());

        $value = [
            'story' => $context->getStory() !== null ? get_class($context->getStory()) : null,
            'interaction' => $context->getInteraction() !== null ? get_class($context->getInteraction()) : null,
            'values' => $context->getValues(),
        ];

        $this->cache->forever($key, $value);
    }

    /**
     * Clear context.
     *
     * @param Context $context
     */
    public function clear(Context $context): void
    {
        $key = $this->key($context->getDriver());

        $this->cache->forget($key);
    }

    /**
     * Get key of current context in storage (Cache, Memory, etc.).
     *
     * @param Driver $driver
     *
     * @return string
     */
    private function key(Driver $driver): string
    {
        return 'context.'.$driver->getChannel()->name.'.'.$driver->getSender()->getIdentifier();
    }
}
