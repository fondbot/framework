<?php

declare(strict_types=1);

namespace FondBot\Contracts\Database\Services;

use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;

interface ParticipantService
{
    /**
     * Find participant by channel id and identifier.
     *
     * @param Channel $channel
     * @param string $identifier
     * @return Participant|\Illuminate\Database\Eloquent\Builder|null
     */
    public function findByChannelAndIdentifier(Channel $channel, string $identifier): ?Participant;
}
