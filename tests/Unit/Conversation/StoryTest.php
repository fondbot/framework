<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use Tests\Classes\Fakes\FakeStory;
use FondBot\Contracts\Channels\Driver;
use Tests\Classes\Fakes\FakeInteraction;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $context
 * @property Story                                      $story
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $interaction
 */
class StoryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->driver = $this->mock(Driver::class);
        $this->context = $this->mock(Context::class);
        $this->story = new FakeStory;
        $this->interaction = $this->mock(FakeInteraction::class);
    }

    public function test_run_no_interaction_in_context()
    {
        $this->context->shouldReceive('getInteraction')->andReturn(null)->once();
        $this->context->shouldReceive('toArray')->andReturn([])->atLeast()->once();

        $this->interaction->shouldReceive('setDriver')->with($this->driver)->once();
        $this->interaction->shouldReceive('setContext')->with($this->context)->once();
        $this->interaction->shouldReceive('run')->once();

        $this->story->setDriver($this->driver);
        $this->story->setContext($this->context);
        $this->story->run();
    }

    public function test_run_has_interaction_in_context()
    {
        $this->context->shouldReceive('getInteraction')->andReturn($this->interaction)->once();
        $this->context->shouldReceive('toArray')->andReturn([])->atLeast()->once();

        $this->interaction->shouldReceive('setDriver')->with($this->driver)->once();
        $this->interaction->shouldReceive('setContext')->with($this->context)->once();
        $this->interaction->shouldReceive('run')->once();

        $this->story->setDriver($this->driver);
        $this->story->setContext($this->context);
        $this->story->run();
    }
}
