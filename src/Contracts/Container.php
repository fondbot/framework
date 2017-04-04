<?php

declare(strict_types=1);

namespace FondBot\Contracts;

interface Container
{
    /**
     * Get instance of the container.
     *
     * @return Container
     */
    public static function instance(): Container;

    /**
     * Register a binding with the container.
     *
     * @param string|array         $abstract
     * @param \Closure|string|null $concrete
     */
    public function bind($abstract, $concrete = null): void;

    /**
     * Register a shared binding in the container.
     *
     * @param string|array         $abstract
     * @param \Closure|string|null $concrete
     */
    public function singleton($abstract, $concrete = null): void;

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     *
     * @return mixed
     */
    public function make(string $abstract);
}
