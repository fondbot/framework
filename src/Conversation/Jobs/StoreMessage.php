<?php

declare(strict_types=1);

namespace FondBot\Conversation\Jobs;

use FondBot\Traits\Loggable;
use Illuminate\Bus\Queueable;
use FondBot\Contracts\Channels\Sender;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Filesystem\Factory;
use FondBot\Contracts\Channels\SenderMessage;
use Illuminate\Contracts\Filesystem\Filesystem;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Entities\Message;
use FondBot\Database\Services\ParticipantService;
use FondBot\Contracts\Channels\Message\Attachment;
use FondBot\Contracts\Database\Entities\Participant;
use FondBot\Contracts\Database\Services\MessageService;

class StoreMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, Loggable;

    private $channel;
    private $sender;
    private $message;

    public function __construct(Channel $channel, Sender $sender, SenderMessage $message)
    {
        $this->channel = $channel;
        $this->sender = $sender;
        $this->message = $message;
    }

    public function handle(ParticipantService $participantService, MessageService $messageService)
    {
        $this->debug('handle', ['channel' => $this->channel->toArray()]);

        // Create participant or update existing
        $participant = $this->createParticipant($participantService);

        // Create message
        $this->createMessage($messageService, $participant);
    }

    private function createParticipant(ParticipantService $service): Participant
    {
        return $service->createOrUpdate([
            'channel_id' => $this->channel->id,
            'identifier' => $this->sender->getIdentifier(),
            'name' => $this->sender->getName(),
            'username' => $this->sender->getUsername(),
        ], ['channel_id' => $this->channel->id, 'identifier' => $this->sender->getIdentifier()]);
    }

    /**
     * @param MessageService $service
     * @param Participant    $participant
     *
     * @return Message|\Illuminate\Database\Eloquent\Model
     */
    private function createMessage(MessageService $service, Participant $participant): Message
    {
        $attachment = $this->storeAttachment($this->message->getAttachment());
        $location = $this->message->getLocation() !== null ? $this->message->getLocation()->toArray() : null;

        return $service->create([
            'sender_id' => $participant->id,
            'text' => $this->message->getText(),
            'attachment' => $attachment,
            'location' => $location,
        ]);
    }

    /**
     * Store attachment using filesystem.
     *
     * @param Attachment|null $attachment
     *
     * @return string
     */
    private function storeAttachment(?Attachment $attachment): ?string
    {
        if ($attachment === null) {
            return null;
        }

        $config = config('fondbot.attachments.filesystem');

        if ($config['enabled'] !== true) {
            return $attachment->getPath();
        }

        /** @var Factory $filesystem */
        $filesystem = resolve(Factory::class);

        /** @var Filesystem|Cloud $disk */
        $disk = $filesystem->disk($config['disk']);
        $disk->makeDirectory($config['folder']);

        $file = $attachment->getFile();

        $disk->put($config['folder'], $file, 'public');

        return $config['folder'].'/'.$file->hashName();
    }
}
