<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Application\Assets;
use League\Container\ServiceProvider\AbstractServiceProvider;

class DriverServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        DriverManager::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function register(): void
    {
        $this->container->share(DriverManager::class, function () {
            // Here we will discover all drivers installed
            // And add all found drivers to the manager

            $manager = new DriverManager($this->container);
            /** @var Assets $assetLoader */
            $assetLoader = $this->container->get(Assets::class);

            $assets = $assetLoader->discover('driver');

            foreach ($assets as $asset) {
                $manager->add($this->container->get($asset));
            }

            return $manager;
        });
    }
}
