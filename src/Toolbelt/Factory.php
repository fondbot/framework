<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use FondBot\Application\Kernel;
use League\Container\Container;
use Symfony\Component\Console\Application;

class Factory
{
    public static function create(Container $container): void
    {
        $kernel = $container->get(Kernel::class);

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

        $container->add('console', $console);
    }
}
