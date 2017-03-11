<?php
declare(strict_types=1);

namespace FondBot\Database\Services;

use FondBot\Database\Entities\Participant;

class ParticipantService extends AbstractService
{

    public function __construct(Participant $entity)
    {
        parent::__construct($entity);
    }

}