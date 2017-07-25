<?php

declare(strict_types=1);

if (!function_exists('kernel')) {
    /**
     * Get kernel instance.
     *
     * @param string|null $resolve
     *
     * @return FondBot\Foundation\Kernel|mixed
     */
    function kernel(string $resolve = null)
    {
        $instance = FondBot\Foundation\Kernel::getInstance();

        if ($resolve !== null) {
            return $instance->resolve($resolve);
        }

        return $instance;
    }
}

if (!function_exists('session')) {
    /**
     * Get session.
     *
     * @return FondBot\Conversation\Session
     */
    function session(): FondBot\Conversation\Session
    {
        return kernel()->getSession();
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
