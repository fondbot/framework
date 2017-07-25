<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Drivers\Exceptions\DriverNotFound;
use FondBot\Drivers\Exceptions\InvalidConfiguration;

class DriverManager
{
    /** @var Driver[] */
    private $drivers = [];

    public function register(array $drivers): void
    {
        /** @var Driver|string $driver */
        foreach ($drivers as $driver) {
            if (!$driver instanceof Driver) {
                $driver = kernel($driver);
            }

            $this->drivers[$driver->getShortName()] = $driver;
        }
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
        $driver = array_get($this->drivers, $name);

        if ($driver === null || !$driver instanceof Driver) {
            throw new DriverNotFound('Driver `'.$name.'` not found.');
        }

        return $driver;
    }
}
