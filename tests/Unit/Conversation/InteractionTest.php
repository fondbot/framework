<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Kernel;
use Tests\TestCase;
use FondBot\Conversation\Context;
use Tests\Classes\TestInteraction;
use FondBot\Drivers\ReceivedMessage;

/**
 * @property mixed|\Mockery\Mock            $kernel
 * @property mixed|\Mockery\Mock            context
 * @property \Tests\Classes\TestInteraction interaction
 */
class InteractionTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->kernel = $this->mock(Kernel::class);
        $this->context = $this->mock(Context::class);

        $this->kernel->shouldReceive('getContext')->andReturn($this->context);

        $this->interaction = new TestInteraction;
    }

    public function test_run_current_interaction_in_context_and_do_not_run_another_interaction()
    {
        $message = $this->mock(ReceivedMessage::class);

        $this->context->shouldReceive('getInteraction')->andReturn($this->interaction)->once();
        $this->context->shouldReceive('getMessage')->andReturn($message)->once();
        $this->context->shouldReceive('setValue')->with('key', 'value')->once();
        $this->kernel->shouldReceive('clearContext')->once();

        $this->interaction->handle($this->kernel);
    }

    public function test_run_current_interaction_not_in_context()
    {
        $this->context->shouldReceive('getInteraction')->andReturnNull()->once();
        $this->context->shouldReceive('setInteraction')->with($this->interaction)->once();

        $this->interaction->handle($this->kernel);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /^Invalid interaction `(.*)`$/
     */
    public function test_run_transition_exception()
    {
        $this->kernel->shouldReceive('get')->andReturn(null)->once();

        $this->interaction->runIncorrect($this->kernel);
    }
}
