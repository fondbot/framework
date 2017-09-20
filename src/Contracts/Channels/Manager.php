<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Channels\Channel;
use Illuminate\Support\Collection;

/**
 * @mixin \Illuminate\Support\Manager
 */
interface Manager
{
    /**
     * Register channels.
     *
     * @param array $channels
     */
    public function register(array $channels): void;

    /**
     * Get all channels.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get channels by driver.
     *
     * @param string $driver
     *
     * @return Collection
     */
    public function getByDriver(string $driver): Collection;

    /**
     * Create channel.
     *
     * @param string $name
     *
     * @return Channel
     */
    public function create(string $name): Channel;
}
