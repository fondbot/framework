<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendRequest implements ShouldQueue
{
    use InteractsWithQueue, Queueable, Dispatchable;

    private $channel;
    private $chat;
    private $recipient;
    private $endpoint;
    private $parameters;

    public function __construct(Channel $channel, Chat $chat, User $recipient, string $endpoint, array $parameters = [])
    {
        $this->channel = $channel;
        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->endpoint = $endpoint;
        $this->parameters = $parameters;
    }

    public function handle(): void
    {
        $driver = $this->channel->getDriver();
        $driver->sendRequest($this->chat, $this->recipient, $this->endpoint, $this->parameters);
    }
}
