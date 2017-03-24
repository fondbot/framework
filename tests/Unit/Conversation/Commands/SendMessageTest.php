<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Commands;

use Tests\TestCase;
use Tests\ModelFactory;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Channels\Receiver;
use FondBot\Conversation\Commands\SendMessage;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\ReceiverMessage;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use FondBot\Contracts\Database\Services\MessageService;
use FondBot\Contracts\Database\Services\ParticipantService;

/**
 * @property Channel                                    $channel
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $receiver
 * @property string                                     $text
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $keyboard
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $driver
 * @property Participant                                $participant
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $receiverMessage
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $channelManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $participantService
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $messageService
 */
class SendMessageTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->channel = ModelFactory::channel();
        $this->receiver = $this->mock(Receiver::class);
        $this->text = $this->faker()->text;
        $this->keyboard = $this->mock(Keyboard::class);

        $this->driver = $this->mock(Driver::class);
        $this->participant = ModelFactory::participant();
        $this->receiverMessage = $this->mock(ReceiverMessage::class);

        $this->channelManager = $this->mock(ChannelManager::class);
        $this->participantService = $this->mock(ParticipantService::class);
        $this->messageService = $this->mock(MessageService::class);
    }

    public function test()
    {
        $this->receiver->shouldReceive('getIdentifier')->andReturn($receiverId = $this->faker()->uuid)->atLeast()->once();

        $this->channelManager->shouldReceive('createDriver')->with($this->channel)->andReturn($this->driver)->once();

        $this->driver->shouldReceive('sendMessage')
            ->with($this->receiver, $this->text, $this->keyboard)
            ->andReturn($this->receiverMessage)
            ->once();

        $this->participantService->shouldReceive('findByChannelAndIdentifier')
            ->with($this->channel, $receiverId)
            ->andReturn($this->participant)
            ->once();

        $this->receiverMessage->shouldReceive('getText')->andReturn($text = $this->faker()->text)->atLeast()->once();

        $this->messageService->shouldReceive('create')->with([
            'receiver_id' => $this->participant->id,
            'text' => $text,
        ])->once();

        $job = new SendMessage($this->channel, $this->receiver, $this->text, $this->keyboard);
        $job->handle($this->channelManager, $this->participantService, $this->messageService);
    }
}
