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
     * @return FondBot\Conversation\Context
     */
    function context(): FondBot\Conversation\Context
    {
        /** @var Manager $conversation */
        $conversation = resolve(Manager::class);

        return $conversation->getContext();
    }
}
