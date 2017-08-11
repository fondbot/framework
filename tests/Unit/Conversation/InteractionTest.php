<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use Mockery\MockInterface;
use FondBot\Tests\TestCase;
use FondBot\Conversation\Session;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Interaction;

class InteractionTest extends TestCase
{
    public function testRunCurrentInteractionInSessionAndDoNotRunAnotherInteraction(): void
    {
        $session = $this->mock(Session::class);
        /** @var Interaction|MockInterface $interaction */
        $interaction = $this->mock(Interaction::class)->makePartial();

        $this->setSession($session);

        $message = $this->mock(MessageReceived::class);

        $session->shouldReceive('getInteraction')->andReturn($interaction)->once();

        $interaction->shouldReceive('process')->with($message)->once();

        $interaction->handle($message);
    }

    public function testRunCurrentInteractionNotInSession(): void
    {
        $session = $this->mock(Session::class);
        /** @var Interaction|MockInterface $interaction */
        $interaction = $this->mock(Interaction::class)->makePartial();

        $this->setSession($session);

        $message = $this->mock(MessageReceived::class);

        $session->shouldReceive('getInteraction')->andReturn(null)->once();
        $session->shouldReceive('setInteraction')->with($interaction)->once();

        $interaction->shouldReceive('run')->with($message)->once();

        $interaction->handle($message);
    }
}
