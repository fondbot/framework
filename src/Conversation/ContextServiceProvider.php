<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Cache;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ContextServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ContextManager::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function register(): void
    {
        $this->container->share(ContextManager::class, function () {
            return new ContextManager(
                $this->container,
                $this->container->get(Cache::class)
            );
        });
    }
}
