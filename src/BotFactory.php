<?php

declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\Channel;
use FondBot\Channels\DriverManager;
use FondBot\Contracts\Container\Container;

class BotFactory
{
    /**
     * Create bot instance.
     *
     * @param Container $container
     * @param Channel   $channel
     * @param array     $request
     * @param array     $headers
     *
     * @return \FondBot\Bot
     */
    public function create(Container $container, Channel $channel, array $request, array $headers): Bot
    {
        /** @var Contracts\Channels\Driver $driver */
        $driver = $container->make(DriverManager::class)->get($channel);

        $driver->fill($channel->getParameters(), $request, $headers);

        return new Bot($container, $channel, $driver, $request, $headers);
    }
}
