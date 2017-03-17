<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use FondBot\Contracts\Channels\Driver;
use FondBot\Channels\Sender;
use FondBot\Conversation\Context;
use Tests\Classes\ExampleInteraction;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Events\MessageSent;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface context
 * @property ExampleInteraction interaction
 */
class InteractionTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->context = $this->mock(Context::class);

        $this->interaction = new ExampleInteraction;
        $this->interaction->setContext($this->context);
    }

    public function test_run_current_interaction_in_context()
    {
        $this->context->shouldReceive('getInteraction')->andReturn($this->interaction);

        $this->interaction->run();
    }

    public function test_run_current_interaction_not_in_context()
    {
        $contextManager = $this->mock(ContextManager::class);
        $driver = $this->mock(Driver::class);
        $sender = Sender::create($this->faker()->uuid, $this->faker()->name, $this->faker()->userName);

        $this->context->shouldReceive('getInteraction')->andReturnNull();
        $this->context->shouldReceive('setInteraction')->with($this->interaction)->once();
        $this->context->shouldReceive('getDriver')->andReturn($driver);
        $contextManager->shouldReceive('save')->with($this->context);

        $driver->shouldReceive('getChannelName')->andReturn($channelName = $this->faker()->userName);
        $driver->shouldReceive('getSender')->andReturn($sender);

        $driver->shouldReceive('sendMessage')->once();

        $this->expectsEvents(MessageSent::class);

        $this->interaction->setContext($this->context);
        $this->interaction->run();
    }
}
