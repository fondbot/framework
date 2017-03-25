<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Entities;

use Tests\TestCase;
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

        $this->channel = $this->factory(Channel::class)->save();
    }

    public function test_participants()
    {
        $participant = $this->factory(Participant::class)->save([
            'channel_id' => $this->channel->id,
        ]);

        $this->assertCount(1, $this->channel->participants);
        $this->assertEquals($participant, $this->channel->participants->first());
    }
}
