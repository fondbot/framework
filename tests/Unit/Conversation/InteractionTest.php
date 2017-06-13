<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Session;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Interaction;

/**
 * @property mixed|\Mockery\Mock                                  $session
 * @property Interaction|\Mockery\Mock $interaction
 */
class InteractionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->session = $this->mock(Session::class);

        $this->kernel->setSession($this->session);

        $this->interaction = $this->mock(Interaction::class)->makePartial();
    }

    public function test_run_current_interaction_in_session_and_do_not_run_another_interaction(): void
    {
        $message = $this->mock(ReceivedMessage::class);

        $this->session->shouldReceive('getInteraction')->andReturn($this->interaction)->once();
        $this->session->shouldReceive('getMessage')->andReturn($message)->once();

        $this->interaction->shouldReceive('process')->with($message)->once();

        $this->interaction->handle($this->kernel);
    }

    public function test_run_current_interaction_not_in_session(): void
    {
        $message = $this->mock(ReceivedMessage::class);

        $this->session->shouldReceive('getInteraction')->andReturn(null)->once();
        $this->session->shouldReceive('setInteraction')->with($this->interaction)->once();
        $this->session->shouldReceive('getMessage')->andReturn($message)->once();

        $this->interaction->shouldReceive('run')->with($message)->once();

        $this->interaction->handle($this->kernel);
    }
}
