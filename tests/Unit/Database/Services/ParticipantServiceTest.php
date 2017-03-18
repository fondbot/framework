<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Services;

use FondBot\Channels\Telegram\TelegramDriver;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use FondBot\Database\Services\ParticipantService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @property Participant $participant
 * @property ParticipantService service
 */
class ParticipantServiceTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->service = new ParticipantService(
            $this->participant = resolve(Participant::class)
        );
    }

    public function test_findByChannelAndIdentifier_null()
    {
        /** @var Channel $channel */
        $channel = Channel::firstOrCreate([
            'driver' => TelegramDriver::class,
            'name' => $this->faker()->userName,
            'parameters' => [],
        ]);
        $identifier = $this->faker()->uuid;

        $result = $this->service->findByChannelAndIdentifier($channel, $identifier);
        $this->assertNull($result);
    }

    public function test_findByChannelAndIdentifier()
    {
        /** @var Channel $channel */
        $channel = Channel::firstOrCreate([
            'driver' => TelegramDriver::class,
            'name' => $this->faker()->userName,
            'parameters' => [],
        ]);

        /** @var Participant $participant */
        $participant = Participant::firstOrCreate([
            'channel_id' => $channel->id,
            'identifier' => $this->faker()->uuid,
        ]);

        $result = $this->service->findByChannelAndIdentifier($channel, $participant->identifier);
        $this->assertSame($participant->id, $result->id);
    }
}
