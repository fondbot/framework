<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use FondBot\Application\Kernel;
use Symfony\Component\Console\Application;
use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class ToolbeltServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'toolbelt',
    ];

    /**
     * Console commands.
     *
     * @return Command[]
     */
    abstract public function commands(): array;

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
        $this->container->share('toolbelt', function () {
            $kernel = $this->container->get(Kernel::class);

            $application = new Application('FondBot', Kernel::VERSION);
            $application->addCommands([
                new Commands\MakeIntent($kernel),
                new Commands\MakeInteraction($kernel),
                new Commands\ListDrivers($kernel),
                new Commands\InstallDriver($kernel),
                new Commands\ListChannels($kernel),
                new Commands\Log($kernel),
                new Commands\QueueWorker($kernel),
            ]);

            foreach ($this->commands() as $command) {
                $application->add($command);
            }

            return $application;
        });
    }
}
