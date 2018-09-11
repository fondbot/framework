<?php

declare(strict_types=1);

namespace FondBot\Contracts;

use FondBot\Events\MessageReceived;

interface Conversable
{
    public function handle(MessageReceived $message): void;
}
