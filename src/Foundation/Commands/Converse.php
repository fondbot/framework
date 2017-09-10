<?php

declare(strict_types=1);

namespace FondBot\Foundation\Commands;

use FondBot\Conversation\Intent;
use FondBot\Events\MessageReceived;
use Illuminate\Foundation\Bus\Dispatchable;
use FondBot\Contracts\Conversation\Conversable;

class Converse
{
    use Dispatchable;

    private $conversable;
    private $messageReceived;

    public function __construct(Conversable $conversable, MessageReceived $messageReceived)
    {
        $this->conversable = $conversable;
        $this->messageReceived = $messageReceived;
    }

    public function handle(): void
    {
        if ($this->conversable instanceof Intent) {
            context()->setIntent($this->conversable)->setInteraction(null);
        }

        $this->conversable->handle($this->messageReceived);
    }
}
