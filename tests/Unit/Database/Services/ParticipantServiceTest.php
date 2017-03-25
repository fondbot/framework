<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Services;

use Tests\TestCase;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Database\Services\ParticipantService;
use FondBot\Contracts\Database\Entities\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @property ParticipantService service
 */
class ParticipantServiceTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new ParticipantService(resolve(Participant::class));
    }

    public function test_findByChannelAndIdentifier_not_found()
    {
        $channel = $this->factory(Channel::class)->save();
        $identifier = $this->faker()->uuid;

        $result = $this->service->findByChannelAndIdentifier($channel, $identifier);
        $this->assertNull($result);
    }

    public function test_findByChannelAndIdentifier()
    {
        $channel = $this->factory(Channel::class)->save();

        /** @var Participant $participant */
        $participant = $this->factory(Participant::class)->save(['channel_id' => $channel->id]);

        $result = $this->service->findByChannelAndIdentifier($channel, $participant->identifier);
        $this->assertSame($participant->id, $result->id);
    }
}
