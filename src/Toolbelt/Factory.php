<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use FondBot\Application\Kernel;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Console\Application;

class Factory
{
    public static function create(Container $container): Kernel
    {
        $container->delegate(new ReflectionContainer);

        // Boot kernel
        $kernel = Kernel::createInstance($container);

        // Boot console application
        $console = new Application('FondBot', Kernel::VERSION);
        $console->addCommands([
            new Commands\MakeIntent($kernel),
            new Commands\MakeInteraction($kernel),
            new Commands\ListDrivers($kernel),
            new Commands\InstallDriver($kernel),
            new Commands\ListChannels($kernel),
            new Commands\Log($kernel),
            new Commands\QueueWorker($kernel),
        ]);

        $container->add(Kernel::class, $kernel);
        $container->add('console', $console);

        return $kernel;
    }
}
