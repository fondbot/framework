<?php

declare(strict_types=1);

namespace FondBot\Database\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Traits\BaseServiceMethods;
use FondBot\Contracts\Database\Entities\Channel as ChannelContract;
use FondBot\Contracts\Database\Services\ChannelService as ChannelServiceContract;

class ChannelService implements ChannelServiceContract
{
    use BaseServiceMethods;

    public function __construct(Channel $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Find all enabled channels.
     *
     * @return Collection
     */
    public function findEnabled(): Collection
    {
        return $this->entity->newQuery()
            ->where('is_enabled', true)
            ->get();
    }

    /**
     * Find all disabled channels.
     *
     * @return Collection
     */
    public function findDisabled(): Collection
    {
        return $this->entity->newQuery()
            ->where('is_enabled', false)
            ->get();
    }

    /**
     * Find channel by name.
     *
     * @param string $name
     * @return ChannelContract|Model|null
     */
    public function findByName(string $name): ?ChannelContract
    {
        return $this->entity
            ->where('name', $name)
            ->first();
    }

    /**
     * Enable channel.
     *
     * @param ChannelContract $channel
     */
    public function enable(ChannelContract $channel): void
    {
        $this->update($channel, [
            'is_enabled' => true,
        ]);
    }

    /**
     * Disable channel.
     *
     * @param ChannelContract $channel
     */
    public function disable(ChannelContract $channel): void
    {
        $this->update($channel, [
            'is_enabled' => false,
        ]);
    }
}
