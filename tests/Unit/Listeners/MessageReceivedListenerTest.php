<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use Storage;
use Tests\TestCase;
use Tests\Classes\Fakes\FakeMessage;
use FondBot\Contracts\Events\MessageReceived;
use FondBot\Contracts\Database\Entities\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MessageReceivedListenerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

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

        Storage::fake('fake');
    }

    public function test_full()
    {
        Participant::unguard();
        $participant = new Participant(['id' => random_int(1, time())]);
        $message = FakeMessage::create();

        event(new MessageReceived($participant, $message));

        $files = Storage::disk('fake')->allFiles();

        $this->assertCount(1, $files);
        $this->assertTrue(ends_with($files[0], '.jpeg'));
        $this->assertDatabaseHas('messages', [
            'sender_id' => $participant->id,
            'receiver_id' => null,
            'text' => $message->getText(),
            'attachment' => $files[0],
            'location' => json_encode($message->getLocation()->toArray()),
            'parameters' => null,
        ]);
    }

    public function test_full_cannot_get_url()
    {
        Participant::unguard();
        $participant = new Participant(['id' => random_int(1, time())]);
        $message = FakeMessage::create();

        event(new MessageReceived($participant, $message));

        $files = Storage::disk('fake')->allFiles();

        $this->assertCount(1, $files);
        $this->assertTrue(ends_with($files[0], '.jpeg'));

        $this->assertDatabaseHas('messages', [
            'sender_id' => $participant->id,
            'receiver_id' => null,
            'text' => $message->getText(),
            'attachment' => $files[0],
            'location' => json_encode($message->getLocation()->toArray()),
            'parameters' => null,
        ]);
    }

    public function test_full_without_location_and_attachment()
    {
        Participant::unguard();
        $participant = new Participant(['id' => random_int(1, time())]);
        $message = new FakeMessage($this->faker()->text());

        event(new MessageReceived($participant, $message));

        $files = Storage::disk('fake')->allFiles();

        $this->assertCount(0, $files);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $participant->id,
            'receiver_id' => null,
            'text' => $message->getText(),
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

        Participant::unguard();
        $participant = new Participant(['id' => random_int(1, time())]);
        $message = FakeMessage::create();

        event(new MessageReceived($participant, $message));

        $files = Storage::disk('fake')->allFiles();

        $this->assertCount(0, $files);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $participant->id,
            'receiver_id' => null,
            'text' => $message->getText(),
            'attachment' => $message->getAttachment()->getPath(),
            'location' => json_encode($message->getLocation()->toArray()),
            'parameters' => null,
        ]);
    }
}
