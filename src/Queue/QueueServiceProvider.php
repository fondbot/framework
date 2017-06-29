<?php

declare(strict_types=1);

namespace FondBot\Queue;

use FondBot\Contracts\Queue;
use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class QueueServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Queue::class,
    ];

    /**
     * Queue adapter.
     *
     * @return Adapter
     */
    abstract public function adapter(): Adapter;

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->share(Queue::class, $this->adapter());
    }
}
