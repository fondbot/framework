<?php
declare(strict_types=1);

namespace Tests\Classes;

use FondBot\Conversation\Story;

class ExampleStory extends Story
{

    /**
     * Story activations
     *
     * @return array
     */
    public function activations(): array
    {
        return [
            '/example',
        ];
    }

    protected function start(): void
    {
        $this->jump(ExampleInteraction::class);
    }

}