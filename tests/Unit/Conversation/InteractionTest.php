<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use Mockery\Mock;
use FondBot\Tests\TestCase;
use FondBot\Conversation\Context;
use FondBot\Conversation\Session;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Tests\Classes\TestInteraction;

/**
 * @property mixed|Mock      $session
 * @property mixed|Mock      $context
 * @property TestInteraction $interaction
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

        $this->interaction = new TestInteraction;
    }

    public function testRunCurrentInteractionInSessionAndDoNotRunAnotherInteraction(): void
    {
        $message = $this->mock(ReceivedMessage::class);

        $this->session->shouldReceive('getInteraction')->andReturn($this->interaction)->once();
        $this->session->shouldReceive('getMessage')->andReturn($message)->once();
        $this->context->shouldReceive('set')->with('key', 'value')->once();

        $this->interaction->handle($this->kernel);
    }

    public function testRunCurrentInteractionNotInSession(): void
    {
        $this->session->shouldReceive('getInteraction')->andReturnNull()->once();
        $this->session->shouldReceive('setInteraction')->with($this->interaction)->once();
        $this->session->shouldReceive('getMessage')->andReturn($this->mock(ReceivedMessage::class))->once();

        $this->interaction->handle($this->kernel);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /^Alias (.*) is not being managed by the container$/
     */
    public function testRunTransitionException(): void
    {
        $this->interaction->runIncorrect($this->kernel);
    }
}
