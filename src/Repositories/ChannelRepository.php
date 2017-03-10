<?php declare(strict_types=1);

namespace FondBot\Database\Repositories;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Database\Abstracts\AbstractRepository;
use FondBot\Database\Entities\Channel;
use Illuminate\Database\Eloquent\Collection;

class ChannelRepository extends AbstractRepository
{

    public function __construct(Channel $entity)
    {
        parent::__construct($entity);
    }

    public function findByDriverAndName(Driver $driver, string $name): ?Channel
    {
        /** @var Channel|null $entity */
        $entity = $this->entity->newQuery()
            ->where('driver', get_class($driver))
            ->where('name', $name)
            ->first();

        return $entity;
    }

    public function enabled(): Collection
    {
        return $this->entity->newQuery()
            ->where('is_enabled', true)
            ->get();
    }

}