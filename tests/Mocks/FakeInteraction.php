<?php

declare(strict_types=1);

namespace FondBot\Tests\Mocks;

use FondBot\Events\MessageReceived;
use FondBot\Conversation\Interaction;

class FakeInteraction extends Interaction
{
    /**
     * Run interaction.
     *
     * @param MessageReceived $message
     */
    public function run(MessageReceived $message): void
    {
        // TODO: Implement run() method.
    }

    /**
     * Process received message.
     *
     * @param MessageReceived $reply
     */
    public function process(MessageReceived $reply): void
    {
        // TODO: Implement process() method.
    }
}
