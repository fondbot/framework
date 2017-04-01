<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Context;
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

    public function test_find_has_intent_in_context()
    {
        $context = $this->mock(Context::class);
        $message = $this->mock(ReceivedMessage::class);
        $intent = $this->mock(Intent::class);

        $context->shouldReceive('getIntent')->andReturn($intent);

        $result = $this->manager->find($context, $message);
        $this->assertSame($intent, $result);
    }

    public function test_find_fallback_intent()
    {
        $this->manager->add(new FakeIntent());
        $this->manager->setFallbackIntent(new FallbackIntent());

        $context = $this->mock(Context::class);
        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('hasAttachment')->andReturn(false);

        $context->shouldReceive('getIntent')->andReturn(null);
        $message->shouldReceive('getText')->andReturn('/start');

        $result = $this->manager->find($context, $message);
        $this->assertInstanceOf(FallbackIntent::class, $result);
    }

    public function test_find_no_intent_in_context_activator_found()
    {
        $this->manager->add(new FakeIntent());

        $context = $this->mock(Context::class);
        $message = $this->mock(ReceivedMessage::class);

        $context->shouldReceive('getIntent')->andReturn(null);
        $message->shouldReceive('getText')->andReturn('/example');

        $result = $this->manager->find($context, $message);
        $this->assertInstanceOf(FakeIntent::class, $result);
    }
}
