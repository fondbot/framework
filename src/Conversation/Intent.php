<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Events\MessageReceived;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\Authorization;
use FondBot\Conversation\Traits\HasActivators;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Contracts\Conversation\Conversable;
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
     * @param MessageReceived $message
     */
    abstract public function run(MessageReceived $message): void;

    /**
     * Handle intent.
     *
     * @param MessageReceived $message
     */
    public function handle(MessageReceived $message): void
    {
        $this->run($message);
    }
}
