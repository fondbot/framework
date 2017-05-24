<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Application\Kernel;
use FondBot\Conversation\Session;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Tests\Classes\TestInteraction;

/**
 * @property mixed|\Mockery\Mock                    $kernel
 * @property mixed|\Mockery\Mock                    $session
 * @property \FondBot\Tests\Classes\TestInteraction interaction
 */
class InteractionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->kernel = $this->mock(Kernel::class);
        $this->session = $this->mock(Session::class);

        $this->kernel->shouldReceive('getSession')->andReturn($this->session);

        $this->interaction = new TestInteraction;
    }

    public function test_run_current_interaction_in_session_and_do_not_run_another_interaction(): void
    {
        $message = $this->mock(ReceivedMessage::class);

        $this->session->shouldReceive('getInteraction')->andReturn($this->interaction)->once();
        $this->session->shouldReceive('getMessage')->andReturn($message)->once();
        $this->session->shouldReceive('setValue')->with('key', 'value')->once();
        $this->kernel->shouldReceive('closeSession')->once();

        $this->interaction->handle($this->kernel);
    }

    public function test_run_current_interaction_not_in_session(): void
    {
        $this->session->shouldReceive('getInteraction')->andReturnNull()->once();
        $this->session->shouldReceive('setInteraction')->with($this->interaction)->once();
        $this->session->shouldReceive('getMessage')->andReturn($this->mock(ReceivedMessage::class))->once();

        $this->interaction->handle($this->kernel);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /^Invalid interaction `(.*)`$/
     */
    public function test_run_transition_exception(): void
    {
        $this->kernel->shouldReceive('resolve')->andReturn(null)->once();

        $this->interaction->runIncorrect($this->kernel);
    }
}
