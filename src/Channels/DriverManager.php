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
     * Create driver instance.
     *
     * @param Channel $channel
     *
     * @return Driver
     */
    public function create(Channel $channel): Driver
    {
        $name = $channel->getParameters()['driver'];

        return $this->drivers[$name];
    }

    /**
     * List of supported drivers.
     *
     * @return array
     */
    public function supportedDrivers(): array
    {
        $drivers = [];

        foreach ($this->drivers as $alias => $instance) {
            $drivers[$alias] = get_class($instance);
        }

        return $drivers;
    }
}
