<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

interface Keyboard
{
    /**
     * Get keyboard buttons.
     *
     * @return Button[]
     */
    public function getButtons(): array;
}
