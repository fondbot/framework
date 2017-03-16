<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Config;
use FondBot\Channels\Message;
use FondBot\Conversation\Context;
use FondBot\Conversation\Story;
use FondBot\Conversation\StoryManager;
use Tests\Classes\ExampleStory;
use Tests\TestCase;

/**
 * @property StoryManager manager
 */
class StoryManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->manager = new StoryManager;
    }

    public function test_find_has_story_in_context()
    {
        $context = $this->mock(Context::class);
        $message = $this->mock(Message::class);
        $story = $this->mock(Story::class);

        $context->shouldReceive('getStory')->andReturn($story);

        $result = $this->manager->find($context, $message);
        $this->assertSame($story, $result);
    }

    public function test_find_no_story_in_context_no_activation_found()
    {
        Config::set('fondbot', [
            'stories' => [
                ExampleStory::class,
            ],
        ]);

        $context = $this->mock(Context::class);
        $message = $this->mock(Message::class);

        $context->shouldReceive('getStory')->andReturn(null);
        $message->shouldReceive('getText')->andReturn('/start');

        $result = $this->manager->find($context, $message);
        $this->assertNull($result);
    }

    public function test_find_no_story_in_context_activation_found()
    {
        Config::set('fondbot', [
            'stories' => [
                ExampleStory::class,
            ],
        ]);

        $context = $this->mock(Context::class);
        $message = $this->mock(Message::class);

        $context->shouldReceive('getStory')->andReturn(null);
        $message->shouldReceive('getText')->andReturn('/example');

        $result = $this->manager->find($context, $message);
        $this->assertInstanceOf(ExampleStory::class, $result);
    }
}
