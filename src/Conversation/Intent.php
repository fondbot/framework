<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Bot;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\Authorization;
use FondBot\Conversation\Traits\HasActivators;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Conversation\Traits\InteractsWithContext;

abstract class Intent implements Conversable
{
    use InteractsWithContext,
        SendsMessages,
        Authorization,
        HasActivators,
        Transitions;

    /**
     * Run intent.
     */
    abstract public function run(): void;

    /**
     * Handle intent.
     *
     * @param Bot $bot
     */
    public function handle(Bot $bot): void
    {
        $this->bot = $bot;
        $this->run();
    }
}
