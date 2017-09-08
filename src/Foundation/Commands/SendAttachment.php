<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use Illuminate\Bus\Queueable;
use FondBot\Templates\Attachment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAttachment implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    private $channel;
    private $chat;
    private $recipient;
    private $attachment;

    public function __construct(Channel $channel, Chat $chat, User $recipient, Attachment $attachment)
    {
        $this->channel = $channel;
        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->attachment = $attachment;
    }

    public function handle(): void
    {
        $driver = $this->channel->getDriver();
        $driver->sendAttachment($this->chat, $this->recipient, $this->attachment);
    }
}
