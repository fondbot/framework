<?php

declare(strict_types=1);

namespace FondBot\Application;

use Psr\SimpleCache\CacheInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class CacheServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        CacheInterface::class,
    ];

    /**
     * Cache adapter.
     *
     * @return CacheInterface
     */
    abstract public function adapter(): CacheInterface;

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->share(CacheInterface::class, $this->adapter());
    }
}
