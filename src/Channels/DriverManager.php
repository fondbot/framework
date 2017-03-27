<?php

declare(strict_types=1);

namespace FondBot\Channels;

use FondBot\Contracts\Channels\Driver;

class DriverManager
{
    /** @var Driver[] */
    private $drivers;

    /**
     * Add driver.
     *
     * @param string $alias
     * @param Driver $instance
     */
    public function add(string $alias, Driver $instance): void
    {
        $this->drivers[$alias] = $instance;
    }

    /**
     * Get driver instance.
     *
     * @param Channel $channel
     *
     * @param array   $request
     * @param array   $headers
     * @param array   $parameters
     *
     * @return Driver
     */
    public function get(Channel $channel, array $request = [], array $headers = [], array $parameters = []): Driver
    {
        $driver = $this->drivers[$channel->getDriver()];

        $driver->fill($parameters, $request, $headers);

        return $driver;
    }
}
