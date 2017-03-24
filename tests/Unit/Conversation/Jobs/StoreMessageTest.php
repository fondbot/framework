<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Jobs;

use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use FondBot\Conversation\Jobs\StoreMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Storage;
use Tests\Classes\Fakes\FakeDriver;
use Tests\Classes\Fakes\FakeSender;
use Tests\Classes\Fakes\FakeSenderMessage;
use Tests\TestCase;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface participantService
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface messageService
 * @property Channel                                    channel
 * @property \Tests\Classes\Fakes\FakeSender            sender
 * @property \Tests\Classes\Fakes\FakeSenderMessage     message
 */
class StoreMessageTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        Storage::fake('fake');

        $this->channel = Channel::firstOrCreate([
            'driver' => FakeDriver::class,
            'name' => $this->faker()->word,
            'parameters' => [],
        ]);
        $this->sender = new FakeSender;
        $this->message = FakeSenderMessage::create();
    }

    public function test_create_participant_and_message_with_location_and_attachment()
    {
        config([
            'fondbot' => [
                'attachments' => [
                    'filesystem' => [
                        'enabled' => true,
                        'disk' => 'fake',
                        'folder' => 'attachments',
                    ],
                ],
            ],
        ]);

        $job = new StoreMessage($this->channel, $this->sender, $this->message);
        dispatch($job);

        $this->assertDatabaseHas('participants', [
            'channel_id' => $this->channel->id,
            'identifier' => $this->sender->getIdentifier(),
            'name' => $this->sender->getName(),
            'username' => $this->sender->getUsername(),
        ]);

        $participant = Participant::first();
        $files = Storage::disk('fake')->allFiles();
        $this->assertCount(1, $files);
        $this->assertTrue(ends_with($files[0], '.jpeg'));
        $this->assertDatabaseHas('messages', [
            'sender_id' => $participant->id,
            'receiver_id' => null,
            'text' => $this->message->getText(),
            'attachment' => $files[0],
            'location' => json_encode($this->message->getLocation()->toArray()),
            'parameters' => null,
        ]);
    }

    public function test_without_location_and_attachment()
    {
        config([
            'fondbot' => [
                'attachments' => [
                    'filesystem' => [
                        'enabled' => true,
                        'disk' => 'fake',
                        'folder' => 'attachments',
                    ],
                ],
            ],
        ]);

        $this->message->withoutAttachment();
        $this->message->withoutLocation();

        $job = new StoreMessage($this->channel, $this->sender, $this->message);
        dispatch($job);

        $participant = Participant::first();
        $files = Storage::disk('fake')->allFiles();
        $this->assertCount(0, $files);
        $this->assertDatabaseHas('messages', [
            'sender_id' => $participant->id,
            'receiver_id' => null,
            'text' => $this->message->getText(),
            'attachment' => null,
            'location' => null,
            'parameters' => null,
        ]);
    }

    public function test_filesystem_disabled()
    {
        config([
            'fondbot' => [
                'attachments' => [
                    'filesystem' => [
                        'enabled' => false,
                    ],
                ],
            ],
        ]);

        $job = new StoreMessage($this->channel, $this->sender, $this->message);
        dispatch($job);

        $participant = Participant::first();
        $files = Storage::disk('fake')->allFiles();
        $this->assertCount(0, $files);
        $this->assertDatabaseHas('messages', [
            'sender_id' => $participant->id,
            'receiver_id' => null,
            'text' => $this->message->getText(),
            'attachment' => $this->message->getAttachment()->getPath(),
            'location' => json_encode($this->message->getLocation()->toArray()),
            'parameters' => null,
        ]);
    }

}
