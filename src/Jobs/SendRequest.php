<?php

declare(strict_types=1);

namespace FondBot\Jobs;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRequest implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    private $chat;
    private $recipient;
    private $endpoint;
    private $parameters;

    public function __construct(Chat $chat, User $recipient, string $endpoint, array $parameters = [])
    {
        $this->chat = $chat;
        $this->recipient = $recipient;
        $this->endpoint = $endpoint;
        $this->parameters = $parameters;
    }

    public function handle(): void
    {
        // TODO
    }
}
