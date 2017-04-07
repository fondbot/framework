<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Channels\Channel;
use FondBot\Drivers\Exceptions\InvalidConfiguration;

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

        $this->validateParameters($channel, $driver);

        $driver->fill($parameters, $request, $headers);

        return $driver;
    }

    /**
     * Validate channel parameters with driver requirements.
     *
     * @param Channel $channel
     * @param Driver  $driver
     */
    private function validateParameters(Channel $channel, Driver $driver): void
    {
        collect($driver->getConfig())->each(function (string $parameter) use ($channel) {
            if ($channel->getParameter($parameter) === null) {
                throw new InvalidConfiguration('Invalid `'.$channel->getName().'` channel configuration.');
            }
        });
    }
}
