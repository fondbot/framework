<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Contracts\Channels\Driver;
use Tests\TestCase;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\ConversationManager;
use FondBot\Contracts\Database\Services\ParticipantService;

class ConversationManagerTest extends TestCase
{
    public function test_start()
    {
        $contextManager = $this->mock(ContextManager::class);
        $participantService = $this->mock(ParticipantService::class);
        $driver = $this->mock(Driver::class);
        $context = $this->mock(Context::class);
        $story = $this->mock(Story::class);

        $context->shouldReceive('setStory')->with($story)->once();
        $contextManager->shouldReceive('save')->with($context)->once();

        $story->shouldReceive('setDriver')->with($driver)->once();
        $story->shouldReceive('setContext')->with($context)->once();
        $story->shouldReceive('run')->once();

        $manager = new ConversationManager($contextManager, $participantService);
        $manager->start($driver, $context, $story);
    }
}
