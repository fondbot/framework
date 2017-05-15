<?php

declare(strict_types=1);

namespace FondBot\Application;

use Dotenv\Dotenv;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

abstract class AppServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $provides = [
        Config::class,
        'environment',
        'base_path',
        'resources_path',
    ];

    /**
     * Determine environment where application is currently is running on.
     *
     * @return string
     */
    abstract public function environment(): string;

    /**
     * Base path of the application.
     *
     * @return string
     */
    abstract public function basePath(): string;

    /**
     * Path to the "resources folder".
     *
     * @return string
     */
    abstract public function resourcesPath(): string;

    /**
     * Method will be invoked on registration of a service provider implementing
     * this interface. Provides ability for eager loading of Service Providers.
     *
     * @return void
     */
    public function boot(): void
    {
        $dotenv = new Dotenv($this->basePath());
        $dotenv->load();

        $this->getContainer()->share(Config::class, function () {
            return new Config($_ENV);
        });
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
        $this->getContainer()->share('environment', $this->environment());
        $this->getContainer()->share('base_path', $this->basePath());
        $this->getContainer()->share('resources_path', $this->resourcesPath());
    }
}
