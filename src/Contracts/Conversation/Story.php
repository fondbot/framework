<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

interface Story
{
    /**
     * Determine if story passes the authorization check.
     *
     * @return bool
     */
    public function passesAuthorization(): bool;

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
