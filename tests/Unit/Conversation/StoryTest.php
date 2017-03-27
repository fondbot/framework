<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use Tests\Classes\Fakes\FakeStory;
use Tests\Classes\Fakes\FakeInteraction;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $bot
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

        $this->bot = $this->mock(Bot::class);
        $this->context = $this->mock(Context::class);
        $this->bot->shouldReceive('getContext')->andReturn($this->context);

        $this->story = new FakeStory;
        $this->interaction = $this->mock(FakeInteraction::class);
    }

    public function test_run_no_interaction_in_context()
    {
        $this->bot->shouldReceive('get')->with(FakeInteraction::class)->andReturn($this->interaction)->once();
        $this->context->shouldReceive('getInteraction')->andReturn(null)->once();
        $this->interaction->shouldReceive('handle')->with($this->bot)->once();

        $this->story->handle($this->bot);
    }

    public function test_run_has_interaction_in_context()
    {
        $this->context->shouldReceive('getInteraction')->andReturn($this->interaction)->once();
        $this->interaction->shouldReceive('handle')->with($this->bot)->once();

        $this->story->handle($this->bot);
    }
}
