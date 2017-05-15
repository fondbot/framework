<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use Dotenv\Dotenv;
use League\Container\Container;
use League\Flysystem\Adapter\Local;
use League\Container\ReflectionContainer;
use Symfony\Component\Console\Application;
use FondBot\Application\LogServiceProvider;
use FondBot\Filesystem\FilesystemServiceProvider;

class Factory
{
    public static function create(
        Container $container,
        string $basePath,
        string $resourcesPath
    ): Kernel {
        $dotenv = new Dotenv($basePath);
        $dotenv->load();

        $container->delegate(new ReflectionContainer);

        $container->add('base_path', $basePath);
        $container->add('resources_path', $resourcesPath);

        // Load service providers
        $container->addServiceProvider(new LogServiceProvider);
        $container->addServiceProvider(new FilesystemServiceProvider(new Local($basePath)));

        // Boot kernel
        $kernel = new Kernel($container);

        // Boot console application
        $console = new Application('FondBot', \FondBot\Application\Kernel::VERSION);
        $console->addCommands([
            new Commands\MakeIntent($kernel),
            new Commands\MakeInteraction($kernel),
            new Commands\ListDrivers($kernel),
            new Commands\InstallDriver($kernel),
            new Commands\Log($kernel),
        ]);

        $container->add(Kernel::class, $kernel);
        $container->add('console', $console);

        return $kernel;
    }
}
