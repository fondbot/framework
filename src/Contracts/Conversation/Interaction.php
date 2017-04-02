<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

use FondBot\Contracts\Channels\ReceivedMessage;

interface Interaction
{
    /**
     * Run interaction.
     */
    public function run(): void;

    /**
     * Process received message.
     *
     * @param ReceivedMessage $reply
     */
    public function process(ReceivedMessage $reply): void;
}
