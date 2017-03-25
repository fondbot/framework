<?php

declare(strict_types=1);

namespace FondBot\Conversation\Commands;

use FondBot\Contracts\Channels\Sender;
use FondBot\Traits\Loggable;
use Illuminate\Bus\Queueable;
use FondBot\Channels\ChannelManager;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Services\MessageService;
use FondBot\Contracts\Database\Services\ParticipantService;

class SendMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, Loggable;

    private $channel;
    private $sender;
    private $text;
    private $keyboard;

    public function __construct(Channel $channel, Sender $sender, string $text, ?Keyboard $keyboard)
    {
        $this->channel = $channel;
        $this->sender = $sender;
        $this->text = $text;
        $this->keyboard = $keyboard;
    }

    public function handle(
        ChannelManager $channelManager,
        ParticipantService $participantService,
        MessageService $messageService
    ) {
        $this->debug('handle');

        $driver = $channelManager->createDriver($this->channel);

        // Send message to receiver
        $message = $driver->sendMessage(
            $this->sender,
            $this->text,
            $this->keyboard
        );

        // Save message in database
        $participant = $participantService->findByChannelAndIdentifier(
            $this->channel,
            $this->sender->getId()
        );

        $messageService->create([
            'receiver_id' => $participant->id,
            'text' => $message->getText(),
        ]);
    }
}
