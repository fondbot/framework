<?php

declare(strict_types=1);

namespace FondBot\Channels;

use FondBot\Channels\Exceptions\ChannelNotFound;

class ChannelManager
{
    /** @var array */
    private $channels = [];

    /**
     * Register channels.
     *
     * @param array $channels
     */
    public function register(array $channels): void
    {
        $this->channels = $channels;
    }

    /**
     * Get all channels.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->channels;
    }

    /**
     * Get channel by name.
     *
     * @param string $name
     *
     * @return Channel
     * @throws ChannelNotFound
     */
    public function get(string $name): Channel
    {
        if (!array_has($this->channels, $name)) {
            throw new ChannelNotFound('Channel `'.$name.'` not found.');
        }

        $data = collect($this->channels[$name]);

        $driver = $data->get('driver');
        $parameters = $data->except('driver')->toArray();

        return new Channel($name, $driver, $parameters);
    }
}
