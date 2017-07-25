<?php

declare(strict_types=1);

namespace FondBot\Conversation;

interface Conversable
{
    /**
     * Handle.
     */
    public function handle(): void;
}
