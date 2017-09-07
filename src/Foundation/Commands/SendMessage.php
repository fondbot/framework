<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use Illuminate\Bus\Queueable;
use InvalidArgumentException;
use FondBot\Contracts\Template;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    private $chat;
    private $recipient;
    private $text;
    private $template;

    public function __construct(Chat $chat, User $recipient, string $text = null, Template $template = null)
    {
        if ($text === null && $template === null) {
            throw new InvalidArgumentException('Either text or template should be set.');
        }

        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->text = $text;
        $this->template = $template;
    }

    public function handle(): void
    {
        // TODO
    }
}
