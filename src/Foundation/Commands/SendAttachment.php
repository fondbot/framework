<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use Illuminate\Bus\Queueable;
use FondBot\Templates\Attachment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAttachment implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    private $chat;
    private $recipient;
    private $attachment;

    public function __construct(Chat $chat, User $recipient, Attachment $attachment)
    {
        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->attachment = $attachment;
    }

    public function handle(): void
    {
        // TODO
    }
}
