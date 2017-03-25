<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Entities;

use Tests\TestCase;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @property Participant $participant
 */
class ParticipantTest extends TestCase
{
    use DatabaseMigrations;

    public function test_channel()
    {
        /** @var Channel $channel */
        $channel = $this->factory(Channel::class)->save();

        $this->participant = $this->factory(Participant::class)->save(['channel_id' => $channel->id]);

        $this->assertInstanceOf(Channel::class, $this->participant->channel);
    }
}
