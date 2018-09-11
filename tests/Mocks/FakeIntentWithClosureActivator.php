<?php

declare(strict_types=1);

namespace FondBot\Tests\Mocks;

use FondBot\Conversation\Intent;
use FondBot\Events\MessageReceived;

class FakeIntentWithClosureActivator extends Intent
{
    /**
     * Intent activators.
     *
     * @return \FondBot\Contracts\Activator[]
     */
    public function activators(): array
    {
        return [
            function (MessageReceived $message) {
                return $message->getText() === 'foo';
            },
        ];
    }

    /**
     * Run intent.
     *
     * @param MessageReceived $message
     */
    public function run(MessageReceived $message): void
    {
        // TODO: Implement run() method.
    }
}
