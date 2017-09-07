<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Channels\Channel;

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
     * @return array
     */
    public function all(): array;

    /**
     * Create channel.
     *
     * @param string $name
     *
     * @return Channel
     */
    public function create(string $name): Channel;
}
