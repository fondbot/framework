<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use FondBot\Channels\Driver;
use FondBot\Channels\Sender;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\ConversationManager;
use FondBot\Contracts\Database\Entities\Channel;
use FondBot\Contracts\Database\Services\ParticipantService;

class ConversationManagerTest extends TestCase
{
    public function test_start()
    {
        $contextManager = $this->mock(ContextManager::class);
        $participantService = $this->mock(ParticipantService::class);
        $context = $this->mock(Context::class);
        $driver = $this->mock(Driver::class);
        $channel = $this->mock(Channel::class);
        $story = $this->mock(Story::class);
        $sender = Sender::create($this->faker()->uuid, $this->faker()->name, $this->faker()->userName);

        $driver->shouldReceive('getSender')->andReturn($sender);
        $this->shouldReturnAttribute($channel, 'id', $channelId = random_int(1, time()));

        $participantService->shouldReceive('createOrUpdate')->with([
            'channel_id' => $channel->id,
            'identifier' => $sender->getIdentifier(),
            'name' => $sender->getName(),
            'username' => $sender->getUsername(),
        ], ['channel_id' => $channelId, 'identifier' => $sender->getIdentifier()]);

        $context->shouldReceive('setStory')->with($story)->once();
        $contextManager->shouldReceive('save')->with($context)->once();

        $story->shouldReceive('run')->with($context)->once();

        $manager = new ConversationManager($contextManager, $participantService);
        $manager->start($context, $driver, $channel, $story);
    }
}
