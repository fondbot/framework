<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Bot;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\Authorization;
use FondBot\Conversation\Traits\HasActivators;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Contracts\Conversation\Conversable;
use FondBot\Conversation\Traits\InteractsWithContext;
use FondBot\Contracts\Conversation\Intent as IntentContract;

abstract class Intent implements IntentContract, Conversable
{
    use InteractsWithContext,
        SendsMessages,
        Authorization,
        HasActivators,
        Transitions;

    /**
     * Handle intent.
     *
     * @param Bot $bot
     */
    final public function handle(Bot $bot): void
    {
        $this->bot = $bot;
        $this->run();
    }
}
