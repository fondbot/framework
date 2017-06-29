<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use League\Container\Container;
use League\Container\ReflectionContainer;
use FondBot\Drivers\DriverServiceProvider;
use FondBot\Conversation\SessionServiceProvider;

class Factory
{
    public static function create(Container $container): Kernel
    {
        $container->delegate(new ReflectionContainer);

        // Load service providers
        $container->addServiceProvider(new DriverServiceProvider);
        $container->addServiceProvider(new SessionServiceProvider);

        // Boot kernel
        $kernel = Kernel::createInstance($container);

        $container->add(Kernel::class, $kernel);

        return $kernel;
    }
}
