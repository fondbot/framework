<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use FondBot\Channels\Channel;
use FondBot\Contracts\Container;
use TheCodingMachine\Discovery\Discovery;
use TheCodingMachine\Discovery\ImmutableAssetType;
use FondBot\Drivers\Exceptions\InvalidConfiguration;

class DriverManager
{
    protected $container;
    protected $discovery;

    /** @var Driver[] */
    protected $drivers;

    public function __construct(Container $container, Discovery $discovery = null)
    {
        $this->container = $container;
        $this->discovery = $discovery ?? Discovery::getInstance();

        $this->boot();
    }

    /**
     * Boot drivers.
     */
    protected function boot(): void
    {
        /** @var ImmutableAssetType $assets */
        $assets = $this->discovery->getAssetType(Driver::class);

        foreach ($assets->getAssets() as $asset) {
            $this->drivers[$asset->getMetadata()['name']] = $this->container->make($asset->getValue());
        }
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
    protected function validateParameters(Channel $channel, Driver $driver): void
    {
        collect($driver->getConfig())->each(function (string $parameter) use ($channel) {
            if ($channel->getParameter($parameter) === null) {
                throw new InvalidConfiguration('Invalid `'.$channel->getName().'` channel configuration.');
            }
        });
    }
}
