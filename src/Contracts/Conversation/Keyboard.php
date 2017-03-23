<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

interface Keyboard
{
    const TYPE_BASIC = 'basic';

    /**
     * Get keyboard type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get keyboard buttons.
     *
     * @return Button[]
     */
    public function getButtons(): array;
}
