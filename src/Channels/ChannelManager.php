<?php

declare(strict_types=1);

namespace FondBot\Channels;

use Illuminate\Support\Manager;
use Illuminate\Support\Collection;
use FondBot\Channels\Exceptions\ChannelNotFound;

/**
 * Class ChannelManager.
 *
 * @method Driver driver($driver = null)
 * @method Driver createDriver($driver)
 */
class ChannelManager extends Manager
{
    /** @var Collection */
    private $channels;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->channels = collect([]);
    }

    /**
     * Register channels.
     *
     * @param array $channels
     */
    public function register(array $channels): void
    {
        $this->channels = collect($channels);
    }

    /**
     * Get all channels.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->channels;
    }

    /**
     * Get channels by driver.
     *
     * @param string $driver
     *
     * @return Collection
     */
    public function getByDriver(string $driver): Collection
    {
        return $this->channels->filter(function (array $channel) use ($driver) {
            return $channel['driver'] === $driver;
        });
    }

    /**
     * Create channel.
     *
     * @param string $name
     *
     * @return Channel
     * @throws ChannelNotFound
     */
    public function create(string $name): Channel
    {
        if (!array_has($this->channels, $name)) {
            throw new ChannelNotFound('Channel `'.$name.'` not found.');
        }

        $parameters = $this->channels[$name];

        // Create driver and initialize it with channel parameters
        $driver = $this->createDriver($parameters['driver']);
        $driver->initialize(collect($parameters)->except('driver'));

        return new Channel($name, $driver, $parameters['webhook-secret'] ?? null);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): ?string
    {
        return null;
    }
}
