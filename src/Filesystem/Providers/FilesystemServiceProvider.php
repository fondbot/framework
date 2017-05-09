<?php

declare(strict_types=1);

namespace FondBot\Filesystem\Providers;

use FondBot\Filesystem\Filesystem;
use League\Flysystem\AdapterInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class FilesystemServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Filesystem::class,
    ];

    private $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
        $filesystem = new Filesystem($this->adapter);

        $this->getContainer()->add(Filesystem::class, $filesystem);
    }
}
