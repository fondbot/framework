<?php

declare(strict_types=1);

namespace FondBot\Channels\Providers;

use FondBot\Channels\ChannelManager;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ChannelServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ChannelManager::class,
    ];

    private $channels;

    public function __construct(array $channels)
    {
        $this->channels = $channels;
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
        $manager = new ChannelManager;

        foreach ($this->channels as $name => $parameters) {
            $manager->add($name, $parameters);
        }

        $this->getContainer()->add(ChannelManager::class, $manager);
    }
}
