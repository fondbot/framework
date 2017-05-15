<?php

declare(strict_types=1);

namespace FondBot\Channels;

use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class ChannelServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ChannelManager::class,
    ];

    /**
     * Define channels.
     *
     * @return array
     */
    abstract public function channels(): array;

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function register(): void
    {
        $this->getContainer()->share(ChannelManager::class, function () {
            $manager = new ChannelManager;

            foreach ($this->channels() as $name => $parameters) {
                $manager->add($name, $parameters);
            }

            return $manager;
        });
    }
}
