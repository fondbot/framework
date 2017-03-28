<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Container;

use FondBot\Contracts\Container\Container as ContainerContract;
use Illuminate\Contracts\Container\Container as LaravelContainer;

class Container implements ContainerContract
{
    private $container;

    public function __construct(LaravelContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Register a binding with the container.
     *
     * @param string|array         $abstract
     * @param \Closure|string|null $concrete
     */
    public function bind($abstract, $concrete = null): void
    {
        $this->container->bind($abstract, $concrete);
    }

    /**
     * Register a shared binding in the container.
     *
     * @param string|array         $abstract
     * @param \Closure|string|null $concrete
     */
    public function singleton($abstract, $concrete = null): void
    {
        $this->container->singleton($abstract, $concrete);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     *
     * @return mixed
     */
    public function make(string $abstract)
    {
        return $this->container->make($abstract);
    }
}
