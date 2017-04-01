<?php

declare(strict_types=1);

namespace FondBot\Conversation\Fallback;

use FondBot\Conversation\Intent;
use FondBot\Contracts\Conversation\Activator;

class FallbackIntent extends Intent
{
    /**
     * Intent activators.
     *
     * @return Activator[]
     */
    public function activators(): array
    {
        return [];
    }

    /**
     * Process intent.
     */
    public function process(): void
    {
        $this->jump(FallbackInteraction::class);
        $this->bot->clearContext();
    }
}
