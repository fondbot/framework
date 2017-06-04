<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Helpers\Arr;
use FondBot\Http\Request;
use FondBot\Channels\Channel;
use Psr\Container\ContainerInterface;
use FondBot\Drivers\Exceptions\DriverNotFound;
use FondBot\Drivers\Exceptions\InvalidConfiguration;

class DriverManager
{
    protected $container;

    /** @var AbstractDriver[] */
    protected $drivers = [];

    /** @var array */
    protected $parameters = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Add driver.
     *
     * @param AbstractDriver $driver
     * @param string         $name
     * @param array          $parameters
     */
    public function add(AbstractDriver $driver, string $name, array $parameters): void
    {
        $this->drivers[$name] = $driver;
        $this->parameters[$name] = $parameters;
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

        $this->validateParameters($channel);

        $driver->initialize($channel->getParameters(), $request);

        return $driver;
    }

    /**
     * Get all installed drivers.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->drivers;
    }

    /**
     * Validate channel parameters with driver requirements.
     *
     * @param Channel $channel
     *
     * @throws InvalidConfiguration
     */
    protected function validateParameters(Channel $channel): void
    {
        $parameters = Arr::get($this->parameters, $channel->getDriver(), []);

        collect($parameters)
            ->each(function (string $parameter) use ($channel) {
                if ($channel->getParameter($parameter) === null) {
                    throw new InvalidConfiguration('Invalid `'.$channel->getName().'` channel configuration.');
                }
            });
    }
}
