<?php

declare(strict_types=1);

namespace FondBot\Database\Services;

use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use FondBot\Contracts\Database\Traits\BaseServiceMethods;
use FondBot\Contracts\Database\Services\ParticipantService as ParticipantServiceContract;

class ParticipantService implements ParticipantServiceContract
{
    use BaseServiceMethods;

    public function __construct(Participant $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Find participant by channel id and identifier.
     *
     * @param Channel $channel
     * @param string $identifier
     * @return Participant|\Illuminate\Database\Eloquent\Builder|null
     */
    public function findByChannelAndIdentifier(Channel $channel, string $identifier): ?Participant
    {
        return $this->entity->newQuery()
            ->where('channel_id', $channel->id)
            ->where('identifier', $identifier)
            ->first();
    }
}
