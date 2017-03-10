<?php declare(strict_types=1);

namespace FondBot\Database\Repositories;

use FondBot\Database\Abstracts\AbstractRepository;
use FondBot\Database\Entities\Channel;
use FondBot\Database\Entities\Participant;

class ParticipantRepository extends AbstractRepository
{

    public function __construct(Participant $entity)
    {
        parent::__construct($entity);
    }

    public function store(Channel $channel, string $identifier, string $name, string $username): Participant
    {
        $attributes = [
            'channel_id' => $channel->id,
            'identifier' => $identifier,
            'name' => $name,
            'username' => $username,
        ];

        $searchBy = ['identifier' => $identifier];

        return $this->createOrUpdate($attributes, $searchBy);
    }

}