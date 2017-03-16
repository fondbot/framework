<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use FondBot\Contracts\Events\MessageReceived;
use FondBot\Contracts\Database\Entities\Participant;
use FondBot\Contracts\Database\Services\MessageService;

class MessageReceivedListenerTest extends TestCase
{
    public function test()
    {
        Participant::unguard();
        $messageService = $this->mock(MessageService::class);
        $participant = new Participant(['id' => random_int(1, time())]);
        $text = $this->faker()->text;

        $messageService->shouldReceive('create')->with([
            'sender_id' => $participant->id,
            'text' => $text,
            'parameters' => [],
        ])->once();

        event(new MessageReceived($participant, $text));
    }
}
