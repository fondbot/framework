<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use Storage;
use Tests\TestCase;
use Tests\Classes\FakeMessage;
use FondBot\Contracts\Events\MessageReceived;
use FondBot\Contracts\Database\Entities\Participant;
use FondBot\Contracts\Database\Services\MessageService;

class MessageReceivedListenerTest extends TestCase
{
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
        $messageService = $this->mock(MessageService::class);
        $participant = new Participant(['id' => random_int(1, time())]);
        $message = FakeMessage::create();

        $messageService->shouldReceive('create')->with([
            'sender_id' => $participant->id,
            'text' => $message->getText(),
            'location' => $message->getLocation()->toArray(),
            'attachment' => $message->getAttachment()->toArray(),
        ])->once();

        event(new MessageReceived($participant, $message));

        $files = Storage::disk('fake')->allFiles();

        $this->assertCount(1, $files);
        $this->assertTrue(ends_with($files[0], '.jpeg'));
    }

    public function test_full_without_location_and_attachment()
    {
        Participant::unguard();
        $messageService = $this->mock(MessageService::class);
        $participant = new Participant(['id' => random_int(1, time())]);
        $message = new FakeMessage($this->faker()->text());

        $messageService->shouldReceive('create')->with([
            'sender_id' => $participant->id,
            'text' => $message->getText(),
            'location' => null,
            'attachment' => null,
        ])->once();

        event(new MessageReceived($participant, $message));

        $files = Storage::disk('fake')->allFiles();

        $this->assertCount(0, $files);
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
        $messageService = $this->mock(MessageService::class);
        $participant = new Participant(['id' => random_int(1, time())]);
        $message = FakeMessage::create();

        $messageService->shouldReceive('create')->with([
            'sender_id' => $participant->id,
            'text' => $message->getText(),
            'location' => $message->getLocation()->toArray(),
            'attachment' => $message->getAttachment()->toArray(),
        ])->once();

        event(new MessageReceived($participant, $message));

        $files = Storage::disk('fake')->allFiles();

        $this->assertCount(0, $files);
    }
}
