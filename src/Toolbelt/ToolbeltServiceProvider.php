<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use FondBot\Foundation\Kernel;
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
     * @throws \Symfony\Component\Console\Exception\LogicException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function register(): void
    {
        $this->container->share('toolbelt', function () {
            $application = new Application('FondBot', Kernel::VERSION);
            $application->addCommands([
                new Commands\MakeIntent,
                new Commands\MakeInteraction,
                new Commands\ListDrivers,
                new Commands\InstallDriver,
                new Commands\ListChannels,
                new Commands\Log,
                new Commands\QueueWorker,
                new Commands\ServerRun,
            ]);

            foreach ($this->commands() as $command) {
                $application->add($command);
            }

            return $application;
        });
    }
}
