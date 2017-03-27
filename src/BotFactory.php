<?php

declare(strict_types=1);

namespace FondBot;

use FondBot\Channels\Channel;
use FondBot\Channels\DriverManager;
use Illuminate\Contracts\Container\Container;

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
        // Create driver
        $driver = $container->make(DriverManager::class)->get($channel);

        return new Bot($container, $channel, $driver, $request, $headers);
    }
}
