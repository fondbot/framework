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
     */
    public function register(): void
    {
        $manager = new ContextManager(
            $this->getContainer(),
            $this->getContainer()->get(Cache::class)
        );

        $this->getContainer()->add(ContextManager::class, $manager);
    }
}
