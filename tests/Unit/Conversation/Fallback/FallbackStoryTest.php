<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Fallback;

use Tests\TestCase;
use FondBot\Conversation\Fallback\FallbackStory;
use FondBot\Conversation\Fallback\FallbackInteraction;

/**
 * @property FallbackStory story
 */
class FallbackStoryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->story = new FallbackStory;
    }

    public function test_activators()
    {
        $this->assertSame([], $this->story->activators());
    }

    public function test_firstInteraction()
    {
        $this->assertSame(FallbackInteraction::class, $this->story->firstInteraction());
    }
}
