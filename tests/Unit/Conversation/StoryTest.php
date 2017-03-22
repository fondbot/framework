<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use Tests\Classes\Fakes\FakeStory;
use FondBot\Conversation\Context;
use FondBot\Conversation\Interaction;
use Tests\Classes\Fakes\FakeInteraction;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface context
 * @property FakeStory story
 */
class StoryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->context = $this->mock(Context::class);
        $this->story = new FakeStory;
    }

    public function test_run_no_interaction_in_context()
    {
        $interaction = $this->mock(FakeInteraction::class);

        $this->context->shouldReceive('getInteraction')->andReturn(null);
        $interaction->shouldReceive('setContext')->with($this->context)->once();
        $interaction->shouldReceive('run')->once();

        $this->story->setContext($this->context);
        $this->story->run();
    }

    public function test_run_has_interaction_in_context()
    {
        $interaction = $this->mock(Interaction::class);

        $this->context->shouldReceive('getInteraction')->andReturn($interaction);
        $interaction->shouldReceive('setContext')->with($this->context)->once();
        $interaction->shouldReceive('run')->once();

        $this->story->setContext($this->context);
        $this->story->run();
    }
}
