<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use Mockery\MockInterface;
use FondBot\Tests\TestCase;
use FondBot\Conversation\Context;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\Interaction;

class InteractionTest extends TestCase
{
    public function testRunCurrentInteractionInSessionAndDoNotRunAnotherInteraction(): void
    {
        $context = $this->mock(Context::class);
        /** @var Interaction|MockInterface $interaction */
        $interaction = $this->mock(Interaction::class)->makePartial();

        $this->setContext($context);

        $message = $this->mock(MessageReceived::class);

        $context->shouldReceive('getInteraction')->andReturn($interaction)->once();

        $interaction->shouldReceive('process')->with($message)->once();

        $interaction->handle($message);
    }

    public function testRunCurrentInteractionNotInSession(): void
    {
        $context = $this->mock(Context::class);
        /** @var Interaction|MockInterface $interaction */
        $interaction = $this->mock(Interaction::class)->makePartial();

        $this->setContext($context);

        $message = $this->mock(MessageReceived::class);

        $context->shouldReceive('getInteraction')->andReturn(null)->once();
        $context->shouldReceive('setInteraction')->with($interaction)->once();

        $interaction->shouldReceive('run')->with($message)->once();

        $interaction->handle($message);
    }
}
