<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\Authorization;
use FondBot\Conversation\Traits\HasActivators;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Conversation\Traits\InteractsWithContext;
use FondBot\Conversation\Traits\InteractsWithSession;

abstract class Intent implements Conversable
{
    use InteractsWithSession,
        InteractsWithContext,
        SendsMessages,
        Authorization,
        HasActivators,
        Transitions;

    /**
     * Run intent.
     *
     * @param ReceivedMessage $message
     */
    abstract public function run(ReceivedMessage $message): void;

    /**
     * Handle intent.
     */
    public function handle(): void
    {
        $this->run(kernel()->getSession()->getMessage());
    }
}
