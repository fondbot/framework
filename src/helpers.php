<?php

declare(strict_types=1);

if (!function_exists('kernel')) {
    /**
     * Get kernel instance.
     *
     * @return \FondBot\Foundation\Kernel
     */
    function kernel(): \FondBot\Foundation\Kernel
    {
        return FondBot\Foundation\Kernel::getInstance();
    }
}

if (!function_exists('resolve')) {
    /**
     * Resolve an alias from container.
     *
     * @param string $alias
     * @param array  $args
     *
     * @return mixed
     */
    function resolve(string $alias, array $args = [])
    {
        return kernel()->resolve($alias, $args);
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

if (!function_exists('path')) {
    /**
     * Get path.
     *
     * @param string $postfix
     *
     * @return string
     */
    function path(string $postfix = null): string
    {
        $path = resolve('base_path');

        if ($postfix === null) {
            return $path;
        }

        return $path.'/'.$postfix;
    }
}

if (!function_exists('resources')) {
    /**
     * Get resources path.
     *
     * @param string $postfix
     *
     * @return string
     */
    function resources(string $postfix = null): string
    {
        $path = resolve('resources_path');

        if ($postfix === null) {
            return $path;
        }

        return $path.'/'.$postfix;
    }
}

if (!function_exists('logger')) {
    /**
     * Get logger.
     *
     * @return Monolog\Logger|Psr\Log\LoggerInterface
     */
    function logger(): Monolog\Logger
    {
        return resolve(Monolog\Logger::class);
    }
}
