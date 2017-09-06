<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

use FondBot\Events\MessageReceived;

interface Conversable
{
    public function handle(MessageReceived $message): void;
}
