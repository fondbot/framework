<?php

declare(strict_types=1);

use FondBot\Contracts\Conversation\Manager;

if (!function_exists('kernel')) {
    /**
     * Get kernel instance.
     *
     * @return FondBot\Foundation\Kernel
     */
    function kernel()
    {
        return resolve(FondBot\Foundation\Kernel::class);
    }
}

if (!function_exists('context')) {
    /**
     * Get context.
     *
     * @param string|null $key
     *
     * @param mixed|null  $default
     *
     * @return \FondBot\Conversation\Context|mixed
     */
    function context(string $key = null, $default = null)
    {
        /** @var Manager $conversation */
        $conversation = resolve(Manager::class);

        $context = $conversation->getContext();

        if ($key === null) {
            return $context;
        }

        return $context->get($key, $default);
    }
}
