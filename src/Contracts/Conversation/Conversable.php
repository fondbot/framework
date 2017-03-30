<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

use FondBot\Bot;

interface Conversable
{
    /**
     * Handle story.
     *
     * @param Bot $bot
     */
    public function handle(Bot $bot): void;
}
