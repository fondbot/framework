<?php

declare(strict_types=1);

namespace FondBot\Database\Services;

use FondBot\Database\Entities\Channel;
use Illuminate\Database\Eloquent\Collection;
use FondBot\Database\Entities\AbstractEntity;

class ChannelService extends AbstractService
{
    public function __construct(Channel $entity)
    {
        parent::__construct($entity);
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
     * Find channel by name.
     *
     * @param string $name
     * @return AbstractEntity|Channel|null
     */
    public function findByName(string $name)
    {
        return $this->entity
            ->where('name', $name)
            ->first();
    }
}
