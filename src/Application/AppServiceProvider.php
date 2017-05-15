<?php

declare(strict_types=1);

namespace FondBot\Application;

use Dotenv\Dotenv;
use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class AppServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Config::class,
        'env',
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
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
        $this->getContainer()->share(Config::class, function () {
            $dotenv = new Dotenv($this->basePath());

            return new Config($dotenv->load());
        });

        $this->getContainer()->share('env', $this->environment());
        $this->getContainer()->share('base_path', $this->basePath());
        $this->getContainer()->share('resources_path', $this->resourcesPath());
    }
}
