<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use League\Container\Container;
use League\Flysystem\Adapter\Local;
use FondBot\Toolbelt\Commands\MakeIntent;
use League\Container\ReflectionContainer;
use Symfony\Component\Console\Application;
use FondBot\Toolbelt\Commands\MakeInteraction;
use FondBot\Filesystem\FilesystemServiceProvider;

class Factory
{
    public static function create(
        Container $container,
        string $basePath,
        string $resourcesPath
    ): Kernel {
        $container->delegate(new ReflectionContainer());

        $container->add('base_path', $basePath);
        $container->add('resources_path', $resourcesPath);
        $container->addServiceProvider(new FilesystemServiceProvider(new Local($basePath)));

        $kernel = new Kernel($container);
        $console = new Application('FondBot', \FondBot\Application\Kernel::VERSION);
        $console->add(new MakeIntent($kernel));
        $console->add(new MakeInteraction($kernel));

        $container->add(Kernel::class, $kernel);
        $container->add('console', $console);

        return $kernel;
    }
}
