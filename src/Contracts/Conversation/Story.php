<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

interface Story
{
    /**
     * Story activators.
     *
     * @return Activator[]
     */
    public function activators(): array;

    /**
     * Interaction should be run firstly.
     *
     * @return string
     */
    public function firstInteraction(): string;
}
