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
    /** @var AbstractDriver[] */
    private $drivers = [];

    /**
     * Add driver.
     *
     * @param AbstractDriver $driver
     */
    public function add(AbstractDriver $driver): void
    {
        $this->drivers[$driver->getShortName()] = $driver;
    }

    /**
     * Get driver for channel.
     *
     * @param Channel $channel
     * @param Request $request
     *
     * @return AbstractDriver
     *
     * @throws InvalidConfiguration
     * @throws DriverNotFound
     */
    public function get(Channel $channel, Request $request): AbstractDriver
    {
        $driver = Arr::get($this->drivers, $channel->getDriver());

        if ($driver === null || !$driver instanceof AbstractDriver) {
            throw new DriverNotFound('Driver `'.$channel->getDriver().'` not found.');
        }

        $driver->initialize($channel->getParameters(), $request);

        return $driver;
    }
}
