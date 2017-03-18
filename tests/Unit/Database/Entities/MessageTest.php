<?php

declare(strict_types=1);

namespace Tests\Unit\Database\Entities;

use Tests\TestCase;
use FondBot\Channels\Telegram\TelegramDriver;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Message;
use FondBot\Contracts\Database\Entities\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @property Message $message
 */
class MessageTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->message = Message::firstOrCreate([
            'driver' => TelegramDriver::class,
            'name' => $this->faker()->name,
            'text' => $this->faker()->text,
            'parameters' => [],
        ]);
    }

    public function test_sender()
    {
        /** @var Channel $channel */
        $channel = Channel::firstOrCreate([
            'driver' => TelegramDriver::class,
            'name' => $this->faker()->name,
            'parameters' => [],
        ]);
        /** @var Participant $participant */
        $participant = $channel->participants()->save(new Participant([
            'identifier' => $this->faker()->uuid,
        ]));

        $this->message->update(['sender_id' => $participant->id]);

        $this->assertInstanceOf(Participant::class, $this->message->sender);
    }

    public function test_receiver()
    {
        /** @var Channel $channel */
        $channel = Channel::firstOrCreate([
            'driver' => TelegramDriver::class,
            'name' => $this->faker()->name,
            'parameters' => [],
        ]);
        /** @var Participant $participant */
        $participant = $channel->participants()->save(new Participant([
            'identifier' => $this->faker()->uuid,
        ]));

        $this->message->update(['receiver_id' => $participant->id]);

        $this->assertInstanceOf(Participant::class, $this->message->receiver);
    }
}
