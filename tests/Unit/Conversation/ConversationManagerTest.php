<?php
declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Channels\Driver;
use FondBot\Channels\Objects\Participant;
use FondBot\Conversation\Context;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\ConversationManager;
use FondBot\Conversation\Story;
use FondBot\Database\Entities\Channel;
use FondBot\Database\Services\ParticipantService;
use Tests\TestCase;

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
        $participant = $this->mock(Participant::class);

        $channelId = random_int(1, time());
        $participantIdentifier = $this->faker()->uuid;
        $participantName = $this->faker()->name;
        $participantUsername = $this->faker()->userName;

        $driver->shouldReceive('getParticipant')->andReturn($participant);

        $this->shouldReturnAttribute($channel, 'id', $channelId);
        $participant->shouldReceive('getIdentifier')->andReturn($participantIdentifier);
        $participant->shouldReceive('getName')->andReturn($participantName);
        $participant->shouldReceive('getUsername')->andReturn($participantUsername);

        $participantService->shouldReceive('createOrUpdate')->with([
            'channel_id' => $channel->id,
            'identifier' => $participantIdentifier,
            'name' => $participantName,
            'username' => $participantUsername,
        ], ['channel_id' => $channelId, 'identifier' => $participantIdentifier]);

        $context->shouldReceive('setStory')->with($story)->once();
        $contextManager->shouldReceive('save')->with($context)->once();

        $story->shouldReceive('run')->with($context)->once();

        $manager = new ConversationManager($contextManager, $participantService);
        $manager->start($context, $driver, $channel, $story);
    }
}
