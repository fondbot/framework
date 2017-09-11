<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use Illuminate\Bus\Queueable;
use InvalidArgumentException;
use FondBot\Contracts\Template;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    private $channel;
    private $chat;
    private $recipient;
    private $text;
    private $template;

    public function __construct(
        Channel $channel,
        Chat $chat,
        User $recipient,
        string $text = null,
        Template $template = null
    ) {
        if ($text === null && $template === null) {
            throw new InvalidArgumentException('Either text or template should be set.');
        }

        $this->channel = $channel;
        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->text = $text;
        $this->template = $template;
    }

    public function handle(): void
    {
        logger('SendMessage.handle');
        $driver = $this->channel->getDriver();
        $driver->sendMessage($this->chat, $this->recipient, $this->text, $this->template);
    }
}
