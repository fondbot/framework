<?php

declare(strict_types=1);

namespace FondBot\Channels;

use FondBot\Database\Entities\Channel;

class ChannelManager
{
    private $drivers = [
        'Telegram' => Drivers\Telegram::class,
    ];

    /**
     * Create driver instance.
     *
     * @param array $request
     * @param Channel $channel
     *
     * @param bool $initialise
     * @return Driver
     */
    public function createDriver(array $request, Channel $channel, bool $initialise = true): Driver
    {
        /** @var Driver $driver */
        $driver = new $channel->driver($channel->name, $channel->parameters);
        $driver->setRequest($request);
        if ($initialise) {
            $driver->init();
        }

        return $driver;
    }

    /**
     * List of supported drivers.
     *
     * @return array
     */
    public function supportedDrivers(): array
    {
        return $this->drivers;
    }
}
