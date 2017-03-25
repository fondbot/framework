<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Commands;

use Tests\TestCase;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\Driver;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Conversation\Commands\SendMessage;
use FondBot\Contracts\Channels\OutgoingMessage;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Participant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use FondBot\Contracts\Database\Services\MessageService;
use FondBot\Contracts\Database\Services\ParticipantService;

/**
 * @property Channel                                    $channel
 * @property Participant                                $participant
 * @property string                                     $text
 * @property User|\Mockery\Mock                         recipient
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $keyboard
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $driver
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

        $this->channel = $this->factory(Channel::class)->create();
        $this->participant = $this->factory(Participant::class)->create();
        $this->text = $this->faker()->text;

        $this->recipient = $this->factory()->sender();
        $this->keyboard = $this->mock(Keyboard::class);
        $this->driver = $this->mock(Driver::class);
        $this->receiverMessage = $this->mock(OutgoingMessage::class);
        $this->channelManager = $this->mock(ChannelManager::class);
        $this->participantService = $this->mock(ParticipantService::class);
        $this->messageService = $this->mock(MessageService::class);
    }

    public function test()
    {
        $this->channelManager->shouldReceive('createDriver')->with($this->channel)->andReturn($this->driver)->once();

        $this->driver->shouldReceive('sendMessage')
            ->with($this->recipient, $this->text, $this->keyboard)
            ->andReturn($this->receiverMessage)
            ->once();

        $this->participantService->shouldReceive('findByChannelAndIdentifier')
            ->with($this->channel, $this->recipient->getId())
            ->andReturn($this->participant)
            ->once();

        $this->receiverMessage->shouldReceive('getText')->andReturn($text = $this->faker()->text)->atLeast()->once();

        $this->messageService->shouldReceive('create')->with([
            'receiver_id' => $this->participant->id,
            'text' => $text,
        ])->once();

        $job = new SendMessage($this->channel, $this->recipient, $this->text, $this->keyboard);
        $job->handle($this->channelManager, $this->participantService, $this->messageService);
    }
}
