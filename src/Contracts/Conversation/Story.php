<?php

declare(strict_types=1);

namespace FondBot\Contracts\Conversation;

interface Story
{
    /**
     * Story activations.
     *
     * @return array
     */
    public function activations(): array;

    /**
     * Interaction class name which will be run when activation is triggered.
     *
     * @return string
     */
    public function firstInteraction(): string;
}