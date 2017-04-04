<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use Tests\Classes\TestIntent;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\FallbackIntent;

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
        $this->manager->add(new TestIntent());
        $this->manager->setFallbackIntent(new FallbackIntent());

        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('hasAttachment')->andReturn(false);
        $message->shouldReceive('getText')->andReturn('/start');

        $result = $this->manager->find($message);
        $this->assertInstanceOf(FallbackIntent::class, $result);
    }

    public function test_find_no_intent_in_context_activator_found()
    {
        $this->manager->add(new TestIntent());

        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('getText')->andReturn('/example');

        $result = $this->manager->find($message);
        $this->assertInstanceOf(TestIntent::class, $result);
    }
}
