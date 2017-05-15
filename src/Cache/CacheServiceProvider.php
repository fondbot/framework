<?php

declare(strict_types=1);

namespace FondBot\Cache;

use FondBot\Contracts\Cache;
use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class CacheServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Cache::class,
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
        $this->getContainer()->share(Cache::class, $this->adapter());
    }
}
