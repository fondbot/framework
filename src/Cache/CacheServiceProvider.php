<?php

declare(strict_types=1);

namespace FondBot\Cache;

use FondBot\Contracts\Cache;
use Psr\SimpleCache\CacheInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class CacheServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Adapter::class,
        CacheInterface::class,
    ];

    /**
     * Cache adapter.
     *
     * @return Adapter
     */
    abstract public function adapter(): Adapter;

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->share(Cache::class, $this->adapter()); // BC
        $this->container->share(CacheInterface::class, $this->adapter());
    }
}
