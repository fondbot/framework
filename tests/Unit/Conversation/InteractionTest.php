<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use Mockery\Mock;
use FondBot\Tests\TestCase;
use FondBot\Conversation\Context;
use FondBot\Conversation\Session;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Interaction;

/**
 * @property mixed|\Mockery\Mock                                  $session
 * @property mixed|Mock      $context
 * @property Interaction|\Mockery\Mock $interaction
 */
class InteractionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->session = $this->mock(Session::class);
        $this->context = $this->mock(Context::class);

        $this->kernel->setSession($this->session);
        $this->kernel->setContext($this->context);

        $this->interaction = $this->mock(Interaction::class)->makePartial();
    }

    public function testRunCurrentInteractionInSessionAndDoNotRunAnotherInteraction(): void
    {
        $message = $this->mock(ReceivedMessage::class);

        $this->session->shouldReceive('getInteraction')->andReturn($this->interaction)->once();
        $this->session->shouldReceive('getMessage')->andReturn($message)->once();

        $this->interaction->shouldReceive('process')->with($message)->once();

        $this->interaction->handle($this->kernel);
    }

    public function testRunCurrentInteractionNotInSession(): void
    {
        $message = $this->mock(ReceivedMessage::class);

        $this->session->shouldReceive('getInteraction')->andReturn(null)->once();
        $this->session->shouldReceive('setInteraction')->with($this->interaction)->once();
        $this->session->shouldReceive('getMessage')->andReturn($message)->once();

        $this->interaction->shouldReceive('run')->with($message)->once();

        $this->interaction->handle($this->kernel);
    }
}
