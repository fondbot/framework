<?php

declare(strict_types=1);

namespace FondBot\Listeners;

use FondBot\Contracts\Channels\Message\Attachment;
use FondBot\Contracts\Events\MessageReceived;
use FondBot\Contracts\Database\Services\MessageService;
use Illuminate\Contracts\Filesystem\Factory;

class MessageReceivedListener
{
    private $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function handle(MessageReceived $event)
    {
        $participant = $event->getParticipant();
        $message = $event->getMessage();

        $this->storeAttachment($message->getAttachment());

        $location = $message->getLocation() !== null ? $message->getLocation()->toArray() : null;
        $attachment = $message->getAttachment() !== null ? $message->getAttachment()->toArray() : null;

        $this->messageService->create([
            'sender_id' => $participant->id,
            'text' => $message->getText(),
            'location' => $location,
            'attachment' => $attachment,
        ]);
    }

    /**
     * Store attachment using filesystem.
     *
     * @param Attachment|null $attachment
     */
    private function storeAttachment(?Attachment $attachment): void
    {
        if ($attachment === null) {
            return;
        }

        $config = config('fondbot.attachments.filesystem');

        if ($config['enabled'] !== true) {
            return;
        }

        /** @var Factory $filesystem */
        $filesystem = resolve(Factory::class);
        $disk = $filesystem->disk($config['disk']);
        $disk->makeDirectory($config['folder']);

        $file = $attachment->getFile();

        $path = $file->hashName($config['folder']);

        $disk->put($path, $file, 'public');
    }
}
