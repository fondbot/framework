<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Bus;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Conversation\Jobs\SendMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\ModelFactory;
use Tests\TestCase;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\Sender;
use FondBot\Conversation\ContextManager;
use Tests\Classes\Fakes\FakeInteraction;
use Tests\Classes\Fakes\FakeSenderMessage;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $context
 * @property Channel                                    $channel
 * @property FakeInteraction                            $interaction
 */
class InteractionTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->context = $this->mock(Context::class);
        $this->channel = ModelFactory::channel();

        $this->interaction = new FakeInteraction;
        $this->interaction->setContext($this->context);
    }

    public function test_getSenderMessage()
    {
        $message = FakeSenderMessage::create();

        $this->context->shouldReceive('getMessage')->andReturn($message)->once();

        $this->assertSame($message, $this->interaction->getSenderMessage());
    }

    public function test_run_current_interaction_in_context_and_do_not_run_another_interaction()
    {
        $contextManager = $this->mock(ContextManager::class);
        $contextManager->shouldReceive('clear')->once();

        $this->context->shouldReceive('getInteraction')->andReturn($this->interaction)->once();
        $this->context->shouldReceive('setValue')->with('key', 'value')->once();
        $contextManager->shouldReceive('save')->once();

        $this->interaction->run();
    }

    public function test_run_current_interaction_not_in_context()
    {
        Bus::fake();

        $contextManager = $this->mock(ContextManager::class);
        $sender = Sender::create($this->faker()->uuid, $this->faker()->name, $this->faker()->userName);

        $this->context->shouldReceive('getSender')->andReturn($sender)->once();
        $this->context->shouldReceive('getChannel')->andReturn($this->channel)->once();
        $this->context->shouldReceive('getInteraction')->andReturnNull()->once();
        $this->context->shouldReceive('setInteraction')->with($this->interaction)->once();
        $contextManager->shouldReceive('save')->with($this->context)->once();

        $this->interaction->setContext($this->context);
        $this->interaction->run();

        Bus::assertDispatched(SendMessage::class);
    }
}
