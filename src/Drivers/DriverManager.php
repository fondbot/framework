<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Helpers\Arr;
use FondBot\Http\Request;
use FondBot\Channels\Channel;
use FondBot\Drivers\Exceptions\DriverNotFound;
use FondBot\Drivers\Exceptions\InvalidConfiguration;

class DriverManager
{
    /** @var Driver[] */
    private $drivers = [];

    /**
     * Add driver.
     *
     * @param Driver $driver
     */
    public function add(Driver $driver): void
    {
        $this->drivers[$driver->getShortName()] = $driver;
    }

    /**
     * Get all registered drivers.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->drivers;
    }

    /**
     * Get driver for channel.
     *
     * @param Channel $channel
     * @param Request $request
     *
     * @return Driver
     *
     * @throws InvalidConfiguration
     * @throws DriverNotFound
     */
    public function get(Channel $channel, Request $request): Driver
    {
        $driver = Arr::get($this->drivers, $channel->getDriver());

        if ($driver === null || !$driver instanceof Driver) {
            throw new DriverNotFound('Driver `'.$channel->getDriver().'` not found.');
        }

        $driver->initialize($channel->getParameters(), $request);

        return $driver;
    }
}
