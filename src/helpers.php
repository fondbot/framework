<?php

declare(strict_types=1);

if (!function_exists('kernel')) {
    /**
     * Get kernel instance.
     *
     * @return FondBot\Foundation\Kernel
     */
    function kernel()
    {
        return Illuminate\Container\Container::getInstance()->get(FondBot\Foundation\Kernel::class);
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
        return kernel()->getContext();
    }
}
