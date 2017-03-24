<?php

declare(strict_types=1);

namespace FondBot\Channels;

use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Database\Entities\Channel;

class ChannelManager
{
    private $drivers;

    public function __construct(array $drivers = [])
    {
        $this->drivers = $drivers;
    }

    /**
     * Create driver instance.
     *
     * @param Channel $channel
     *
     * @param array $request
     * @param array $headers
     *
     * @return \FondBot\Contracts\Channels\Driver
     */
    public function createDriver(Channel $channel, array $request = [], array $headers = []): Driver
    {
        /** @var Driver $driver */
        $driver = resolve($channel->driver);
        $driver->setParameters($channel->parameters);
        $driver->setRequest($request);
        $driver->setHeaders($headers);

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
