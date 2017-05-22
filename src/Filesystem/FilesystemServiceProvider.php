<?php

declare(strict_types=1);

namespace FondBot\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class FilesystemServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        MountManager::class,
    ];

    /**
     * Filesystem adapters.
     *
     * @return AdapterInterface[]
     */
    abstract public function adapters(): array;

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \LogicException
     */
    public function register(): void
    {
        $this->container->share(MountManager::class, function () {
            $filesystems = [
                'local' => new Filesystem(new Local($this->container->get('base_path'))),
            ];

            foreach ($this->adapters() as $name => $adapter) {
                $filesystems[$name] = new Filesystem($adapter);
            }

            return new MountManager($filesystems);
        });
    }
}
