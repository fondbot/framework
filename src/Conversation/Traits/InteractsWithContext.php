<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

trait InteractsWithContext
{
    /**
     * Get the whole context or a single value.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return array|mixed
     */
    protected function context(string $key = null, $default = null)
    {
        $context = context();

        if ($key === null) {
            return $context;
        }

        return $context->get($key, $default);
    }

    /**
     * Remember value in context.
     *
     * @param string $key
     * @param mixed  $value
     */
    protected function remember(string $key, $value): void
    {
        $context = context();

        $context->set($key, $value);
    }
}
