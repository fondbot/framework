<?php

declare(strict_types=1);

namespace FondBot\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\AdapterInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class FilesystemServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Filesystem::class,
    ];

    /**
     * Filesystem adapter.
     *
     * @return \League\Flysystem\AdapterInterface
     */
    abstract public function adapter(): AdapterInterface;

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->share(Filesystem::class, function () {
            return new Filesystem($this->adapter());
        });
    }
}
