<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use FondBot\Conversation\ConversationManager;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

abstract class AppServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $provides = [
        'environment',
        'base_path',
        'resources_path',
        'bootstrap_path',
        ConversationManager::class,
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
        try {
            (new Dotenv($this->basePath()))->load();
        } catch (InvalidPathException $exception) {
        }
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
        $this->container->share('environment', $this->environment());
        $this->container->share('base_path', $this->basePath());
        $this->container->share('resources_path', $this->resourcesPath());
        $this->container->share('bootstrap_path', 'bootstrap');

        $this->container->share(ConversationManager::class, function () {
            return new ConversationManager($this->container->get(Kernel::class));
        });
    }
}
