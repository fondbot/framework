<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use League\Container\Container;

class Kernel
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve from container.
     *
     * @param string $class
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function resolve(string $class)
    {
        return $this->container->get($class);
    }
}
