<?php

declare(strict_types=1);

namespace FondBot\Application;

use Throwable;
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
        'application_log',
    ];

    /**
     * Method will be invoked on registration of a service provider implementing
     * this interface. Provides ability for eager loading of Service Providers.
     *
     * @return void
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function boot(): void
    {
        $path = $this->getContainer()->get('resources_path').'/logs/app.log';

        $this->container->share('application_log', $path);

        $this->getContainer()->share(LoggerInterface::class, function () use ($path) {
            $logger = new Logger('FondBot');
            $logger->pushHandler(new StreamHandler($path));

            return $logger;
        });

        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->pushHandler(function (Throwable $exception) {
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
