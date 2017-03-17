<?php

declare(strict_types=1);

namespace FondBot\Conversation\Fallback;

use FondBot\Conversation\Story;

class FallbackStory extends Story
{
    protected $name = 'defaultFallbackStory';

    /**
     * Story activations.
     *
     * @return array
     */
    public function activations(): array
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
        $this->clearContext();
    }
}
