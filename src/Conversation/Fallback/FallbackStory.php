<?php

declare(strict_types=1);

namespace FondBot\Conversation\Fallback;

use FondBot\Conversation\Story;
use FondBot\Contracts\Conversation\Activator;

class FallbackStory extends Story
{
    /**
     * Story activators.
     *
     * @return Activator[]
     */
    public function activators(): array
    {
        return [];
    }

    /**
     * Interaction class name which will be run when activation is triggered.
     *
     * @return string
     */
    public function firstInteraction(): string
    {
        return FallbackInteraction::class;
    }

    /**
     * Do something after running Story.
     */
    protected function after(): void
    {
        $this->bot->clearContext();
    }
}
