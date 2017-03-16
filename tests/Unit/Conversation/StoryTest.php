<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use Tests\Classes\ExampleStory;
use FondBot\Conversation\Context;
use FondBot\Conversation\Interaction;
use Tests\Classes\ExampleInteraction;

/**
 * @property ExampleStory story
 */
class StoryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->story = new ExampleStory;
    }

    public function test_run_no_interaction_in_context()
    {
        $context = $this->mock(Context::class);
        $context->shouldReceive('getInteraction')->andReturn(null);

        $interaction = $this->mock(ExampleInteraction::class);
        $interaction->shouldReceive('setContext')->with($context)->once();
        $interaction->shouldReceive('run')->once();

        $this->story->run($context);
    }

    public function test_run_has_interaction_in_context()
    {
        $context = $this->mock(Context::class);
        $interaction = $this->mock(Interaction::class);

        $context->shouldReceive('getInteraction')->andReturn($interaction);
        $interaction->shouldReceive('setContext')->with($context)->once();
        $interaction->shouldReceive('run')->once();

        $this->story->run($context);
    }
}
