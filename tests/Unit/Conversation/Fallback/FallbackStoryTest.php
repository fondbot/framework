<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Fallback;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\Driver;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\Fallback\FallbackStory;
use FondBot\Conversation\Fallback\FallbackInteraction;

/**
 * @property FallbackStory story
 */
class FallbackStoryTest extends TestCase
{
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

    public function test_process()
    {
        $interaction = $this->mock(FallbackInteraction::class);
        $contextManager = $this->mock(ContextManager::class);
        $context = $this->mock(Context::class);

        Bot::createInstance($this->container, $this->mock(Channel::class), $this->mock(Driver::class), [], []);
        Bot::getInstance()->setContext($context);

        $interaction->shouldReceive('handle')->once();
        $context->shouldReceive('setInteraction')->with($interaction)->once();
        $contextManager->shouldReceive('clear')->with($context)->once();

        $this->story->handle(Bot::getInstance());
    }
}
