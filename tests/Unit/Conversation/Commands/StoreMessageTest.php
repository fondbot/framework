<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Commands;

use FondBot\Contracts\Channels\SenderMessage;
use Storage;
use Tests\Factory;
use Tests\TestCase;
use FondBot\Conversation\Commands\StoreMessage;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @property Channel                                   channel
 * @property \FondBot\Contracts\Channels\Sender        sender
 * @property \FondBot\Contracts\Channels\SenderMessage message
 */
class StoreMessageTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        Storage::fake('fake');

        $this->channel = $this->factory(Channel::class)->save();
        $this->sender = $this->factory()->sender();
        $this->message = $this->factory()->senderMessage();
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

        $this->message = $this->factory()->senderMessage([
            'location' => null,
            'attachment' => null,
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
