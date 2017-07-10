<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use Psr\SimpleCache\CacheInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class SessionServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        SessionManager::class,
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
        $this->container->share(SessionManager::class, function () {
            return new SessionManager(
                $this->container,
                $this->container->get(CacheInterface::class)
            );
        });
    }
}
