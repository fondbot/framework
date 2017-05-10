<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use TheCodingMachine\Discovery\Discovery;
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
     */
    public function register(): void
    {
        $manager = new DriverManager($this->getContainer(), Discovery::getInstance());

        $this->getContainer()->add(DriverManager::class, $manager);
    }
}
