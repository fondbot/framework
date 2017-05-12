<?php

declare(strict_types=1);

namespace FondBot\Application;

use Dotenv\Dotenv;
use League\Container\Container;
use League\Flysystem\Adapter\Local;
use League\Container\ReflectionContainer;
use FondBot\Drivers\DriverServiceProvider;
use FondBot\Channels\ChannelServiceProvider;
use FondBot\Conversation\IntentServiceProvider;
use FondBot\Conversation\ContextServiceProvider;
use FondBot\Filesystem\FilesystemServiceProvider;

class Factory
{
    public static function create(
        Container $container,
        string $basePath,
        string $resourcesPath,
        string $routesPrefix = ''
    ): Kernel {
        $dotenv = new Dotenv($basePath);
        $dotenv->load();

        $container->delegate(new ReflectionContainer);

        $container->add('base_path', $basePath);
        $container->add('resources_path', $resourcesPath);

        // Load service providers
        $container->addServiceProvider(new LogServiceProvider);
        $container->addServiceProvider(new RouteServiceProvider($routesPrefix));
        $container->addServiceProvider(new FilesystemServiceProvider(new Local($basePath)));
        $container->addServiceProvider(new DriverServiceProvider);
        $container->addServiceProvider(new ChannelServiceProvider);
        $container->addServiceProvider(new IntentServiceProvider);
        $container->addServiceProvider(new ContextServiceProvider);

        // Boot kernel
        $kernel = new Kernel($container);

        $container->add(Kernel::class, $kernel);

        return $kernel;
    }
}
