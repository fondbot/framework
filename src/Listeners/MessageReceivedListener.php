<?php

declare(strict_types=1);

namespace FondBot\Listeners;

use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Filesystem\Factory;
use FondBot\Contracts\Events\MessageReceived;
use FondBot\Contracts\Channels\Message\Attachment;
use FondBot\Contracts\Database\Services\MessageService;
use Illuminate\Filesystem\Filesystem;

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

        $attachment = $this->storeAttachment($message->getAttachment());
        $location = $message->getLocation() !== null ? $message->getLocation()->toArray() : null;

        $this->messageService->create([
            'sender_id' => $participant->id,
            'text' => $message->getText(),
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
        $disk->makeDirectory($config['folder'], 0777, true, true);

        $file = $attachment->getFile();

        $disk->put($config['folder'], $file, 'public');

        $path = $config['folder'].'/'.$file->hashName();

        return $disk->url($path);
    }
}
