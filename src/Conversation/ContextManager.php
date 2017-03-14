<?php
declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Channels\Driver;

class ContextManager
{

    /**
     * Resolve context instance
     *
     * @param Driver $driver
     * @return Context
     */
    public function resolve(Driver $driver): Context
    {
        $key = $this->key($driver);

        $value = cache($key);

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
     * Save updated context
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

        cache()->put($key, $value);
    }

    /**
     * Get key of current context in storage (Cache, Memory, etc.)
     *
     * @param Driver $driver
     * @return string
     */
    private function key(Driver $driver): string
    {
        return 'context.' . $driver->getChannelName() . '.' . $driver->getParticipant()->getIdentifier();
    }

}