<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use FondBot\Contracts\Channels\ReceiverMessage;
use Tests\TestCase;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\Receiver;
use FondBot\Contracts\Events\MessageSent;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use FondBot\Contracts\Database\Services\MessageService;
use FondBot\Contracts\Database\Services\ParticipantService;

class MessageSentListenerTest extends TestCase
{
    public function test()
    {
        $participantService = $this->mock(ParticipantService::class);
        $messageService = $this->mock(MessageService::class);
        $context = $this->mock(Context::class);
        $receiver = $this->mock(Receiver::class);
        $message = $this->mock(ReceiverMessage::class);
        $channel = new Channel();
        $participant = new Participant(['id' => random_int(1, time())]);

        $receiver->shouldReceive('getIdentifier')->andReturn($this->faker()->uuid)->atLeast()->once();
        $message->shouldReceive('getText')->andReturn($this->faker()->text)->atLeast()->once();
        $message->shouldReceive('getReceiver')->andReturn($receiver)->once();

        $context->shouldReceive('getChannel')->andReturn($channel)->once();

        $participantService->shouldReceive('findByChannelAndIdentifier')
            ->with($channel, $receiver->getIdentifier())
            ->andReturn($participant)
            ->once();

        $messageService->shouldReceive('create')->with([
            'receiver_id' => $participant->id,
            'text' => $message->getText(),
        ])->once();

        event(new MessageSent($context, $message));
    }
}
