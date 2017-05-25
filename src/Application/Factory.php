<?php

declare(strict_types=1);

namespace FondBot\Application;

use League\Container\Container;
use League\Container\ReflectionContainer;
use FondBot\Drivers\DriverServiceProvider;
use FondBot\Conversation\SessionServiceProvider;
use FondBot\Conversation\ConversationServiceProvider;

class Factory
{
    public static function create(Container $container, string $routesPrefix = ''): Kernel
    {
        $container->delegate(new ReflectionContainer);

        // Load service providers
        $container->addServiceProvider(new RouteServiceProvider($routesPrefix));
        $container->addServiceProvider(new DriverServiceProvider);
        $container->addServiceProvider(new SessionServiceProvider);
        $container->addServiceProvider(new ConversationServiceProvider);

        // Boot kernel
        $kernel = Kernel::createInstance($container);

        $container->add(Kernel::class, $kernel);

        return $kernel;
    }
}
