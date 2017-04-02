<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use Tests\Classes\Fakes\FakeIntent;
use FondBot\Conversation\IntentManager;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Conversation\Fallback\FallbackIntent;

/**
 * @property IntentManager manager
 */
class IntentManagerTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->manager = new IntentManager();
    }

    public function test_find_fallback_intent()
    {
        $this->manager->add(new FakeIntent());
        $this->manager->setFallbackIntent(new FallbackIntent());

        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('hasAttachment')->andReturn(false);
        $message->shouldReceive('getText')->andReturn('/start');

        $result = $this->manager->find($message);
        $this->assertInstanceOf(FallbackIntent::class, $result);
    }

    public function test_find_no_intent_in_context_activator_found()
    {
        $this->manager->add(new FakeIntent());

        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('getText')->andReturn('/example');

        $result = $this->manager->find($message);
        $this->assertInstanceOf(FakeIntent::class, $result);
    }
}
