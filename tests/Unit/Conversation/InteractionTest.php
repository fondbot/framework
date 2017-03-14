<?php
declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Channels\Abstracts\Driver;
use FondBot\Channels\Objects\Participant;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use Tests\Classes\ExampleInteraction;
use Tests\TestCase;

/**
 * @property ExampleInteraction interaction
 */
class InteractionTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();

        $this->interaction = new ExampleInteraction;
    }

    public function test_run_current_interaction_in_context()
    {
        $context = $this->mock(Context::class);
        $context->shouldReceive('getInteraction')->andReturn($this->interaction);

        $this->interaction->run($context);
    }

    public function test_run_current_interaction_not_in_context()
    {
        $context = $this->mock(Context::class);
        $contextManager = $this->mock(ContextManager::class);
        $driver = $this->mock(Driver::class);
        $participant = $this->mock(Participant::class);

        $context->shouldReceive('getInteraction')->andReturnNull();
        $context->shouldReceive('setInteraction')->with($this->interaction)->once();
        $contextManager->shouldReceive('save')->with($context);
        $context->shouldReceive('getDriver')->andReturn($driver);
        $driver->shouldReceive('getParticipant')->andReturn($participant);
        $driver->shouldReceive('reply')->once();

        $this->interaction->run($context);
    }

}
