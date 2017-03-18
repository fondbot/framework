<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Entities;

use Tests\TestCase;
use FondBot\Channels\Telegram\TelegramDriver;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @property Participant $participant
 */
class ParticipantTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->participant = Participant::firstOrCreate([
            'channel_id' => 0,
            'identifier' => $this->faker()->uuid,
        ]);
    }

    public function test_channel()
    {
        /** @var Channel $channel */
        $channel = Channel::firstOrCreate([
            'driver' => TelegramDriver::class,
            'name' => $this->faker()->name,
            'parameters' => [],
        ]);

        $this->participant->update(['channel_id' => $channel->id]);

        $this->assertInstanceOf(Channel::class, $this->participant->channel);
    }
}
