<?php

declare(strict_types=1);

namespace FondBot\Contracts\Database\Services;

use Illuminate\Database\Eloquent\Collection;
use FondBot\Contracts\Database\Entities\Channel;

/**
 * Interface ChannelService
 *
 * @mixin \FondBot\Contracts\Database\Traits\BaseServiceMethods
 *
 * @package FondBot\Contracts\Database\Services
 */
interface ChannelService
{
    /**
     * Find all enabled channels.
     *
     * @return Collection
     */
    public function findEnabled(): Collection;

    /**
     * Find all disabled channels.
     *
     * @return Collection
     */
    public function findDisabled(): Collection;

    /**
     * Find channel by name.
     *
     * @param string $name
     * @return Channel|null
     */
    public function findByName(string $name): ?Channel;

    /**
     * Enable channel.
     *
     * @param Channel $channel
     */
    public function enable(Channel $channel): void;

    /**
     * Disable channel.
     *
     * @param Channel $channel
     */
    public function disable(Channel $channel): void;

}
