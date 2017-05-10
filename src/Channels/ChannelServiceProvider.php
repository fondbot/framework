<?php

declare(strict_types=1);

namespace FondBot\Channels;

use FondBot\Application\Config;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ChannelServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ChannelManager::class,
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
        /** @var Config $config */
        $config = $this->getContainer()->get(Config::class);
        /** @var array $channels */
        $channels = $config->get('channels', []);

        $manager = new ChannelManager;

        foreach ($channels as $name => $parameters) {
            $manager->add($name, $parameters);
        }

        $this->getContainer()->add(ChannelManager::class, $manager);
    }
}
