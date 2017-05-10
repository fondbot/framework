<?php

declare(strict_types=1);

namespace FondBot\Application;

use Exception;
use Whoops\Run;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Whoops\Handler\PrettyPageHandler;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class LogServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $provides = [
        LoggerInterface::class,
    ];

    /**
     * Method will be invoked on registration of a service provider implementing
     * this interface. Provides ability for eager loading of Service Providers.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->getContainer()->share(LoggerInterface::class, function () {
            $logger = new Logger('FondBot');
            $logger->pushHandler(new StreamHandler(
                $this->getContainer()->get('resources_path').'/app.log'
            ));

            return $logger;
        });

        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->pushHandler(function (Exception $exception, $inspector, $run) {
            /** @var LoggerInterface $logger */
            $logger = $this->getContainer()->get(LoggerInterface::class);

            $logger->error($exception);
        });
        $whoops->register();
    }

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
    }
}
