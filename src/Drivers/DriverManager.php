<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Helpers\Arr;
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
     * @param string $name
     *
     * @return Driver
     *
     * @throws InvalidConfiguration
     * @throws DriverNotFound
     */
    public function get(string $name): Driver
    {
        $driver = Arr::get($this->drivers, $name);

        if ($driver === null || !$driver instanceof Driver) {
            throw new DriverNotFound('Driver `'.$name.'` not found.');
        }

        return $driver;
    }
}
