<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Helpers\Arr;
use TheCodingMachine\Discovery\Discovery;
use League\Container\ServiceProvider\AbstractServiceProvider;

class DriverServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        DriverManager::class,
    ];

    private $discovery;

    public function __construct(Discovery $discovery = null)
    {
        $this->discovery = $discovery ?? Discovery::getInstance();
    }

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
            $manager = new DriverManager($this->container);

            // Here we will discover all drivers installed
            // And add all found drivers to the manager
            $assets = $this->discovery->getAssetType(AbstractDriver::class);

            foreach ($assets->getAssets() as $asset) {
                $manager->add(
                    $this->container->get($asset->getValue()),
                    $asset->getMetadata()['name'],
                    Arr::get($asset->getMetadata(), 'parameters', [])
                );
            }

            return $manager;
        });
    }
}
