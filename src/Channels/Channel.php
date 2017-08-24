<?php

declare(strict_types=1);

namespace FondBot\Channels;

class Channel
{
    private $name;
    private $driver;

    public function __construct(string $name, Driver $driver)
    {
        $this->name = $name;
        $this->driver = $driver;
    }

    /**
     * Name of the channel.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get driver.
     *
     * @return Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }
}
