<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Fallback;

use Bus;
use FondBot\Conversation\Jobs\SendMessage;
use Tests\TestCase;
use FondBot\Conversation\Context;
use Tests\Classes\Fakes\FakeDriver;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\Fallback\FallbackStory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use FondBot\Conversation\Fallback\FallbackInteraction;

/**
 * @property \FondBot\Conversation\Fallback\FallbackStory story
 */
class FallbackStoryTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->story = new FallbackStory;
    }

    public function test_activations()
    {
        $this->assertSame([], $this->story->activations());
    }

    public function test_firstInteraction()
    {
        $this->assertSame(FallbackInteraction::class, $this->story->firstInteraction());
    }

    public function test_after()
    {
        Bus::fake();

        $driver = new FakeDriver();
        $context = new Context($driver->getChannel(), $driver->getSender(), $driver->getMessage());

        $contextManager = $this->mock(ContextManager::class);
        $contextManager->shouldReceive('save')->once();
        $contextManager->shouldReceive('clear')->once();

        $this->story->setContext($context);
        $this->story->setDriver($driver);
        $this->story->run();

        Bus::assertDispatched(SendMessage::class);
    }
}
