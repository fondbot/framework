<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Entities;

use Tests\TestCase;
use FondBot\Channels\Telegram\TelegramDriver;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @property Channel channel
 */
class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->channel = Channel::firstOrCreate([
            'driver' => TelegramDriver::class,
            'name' => $this->faker()->name,
            'parameters' => [],
        ]);
    }

    public function test_participants()
    {
        Participant::firstOrCreate([
            'channel_id' => $this->channel->id,
            'identifier' => $this->faker()->uuid,
        ]);

        $this->assertCount(1, $this->channel->participants);
    }
}
