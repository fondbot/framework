<?php

declare(strict_types=1);

namespace Tests\Classes;

use FondBot\Conversation\Story;

class ExampleStory extends Story
{
    /**
     * Story activations.
     *
     * @return array
     */
    public function activations(): array
    {
        return [
            '/example',
        ];
    }

    /**
     * Interaction class name which will be run when activation is triggered.
     *
     * @return string
     */
    public function firstInteraction(): string
    {
        return ExampleInteraction::class;
    }

}
