<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use FondBot\Conversation\Story;
use VerbalExpressions\PHPVerbalExpressions\VerbalExpressions;

class FakeStory extends Story
{
    /**
     * Story activations.
     *
     * @return array
     */
    public function activations(): array
    {
        return [
            $this->exact('/example'),
            $this->pattern('/\/example/'),
            $this->pattern((new VerbalExpressions())->startOfLine()->then('/example')->endOfLine()),
            $this->inArray(['/example']),
            $this->inArray(collect(['/example'])),
        ];
    }

    /**
     * Interaction class name which will be run when activation is triggered.
     *
     * @return string
     */
    public function firstInteraction(): string
    {
        return FakeInteraction::class;
    }
}
