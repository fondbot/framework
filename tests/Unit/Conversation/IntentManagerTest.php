<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Intent;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\FallbackIntent;
use FondBot\Conversation\Activators\Activator;

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

    public function test_returns_fallback_intent_if_no_intent_found()
    {
        $this->manager->add($intent = $this->mock(Intent::class));
        $this->manager->setFallbackIntent(new FallbackIntent());

        $intent->shouldReceive('activators')->andReturn([$activator = $this->mock(Activator::class)])->once();
        $activator->shouldReceive('matches')->andReturn(false)->once();

        $message = $this->mock(ReceivedMessage::class);

        $result = $this->manager->find($message);
        $this->assertInstanceOf(FallbackIntent::class, $result);
    }

    public function test_finds_intent_by_activator()
    {
        $this->manager->add($intent = $this->mock(Intent::class));

        $intent->shouldReceive('activators')->andReturn([$activator = $this->mock(Activator::class)])->once();
        $intent->shouldReceive('passesAuthorization')->andReturn(true)->once();
        $activator->shouldReceive('matches')->andReturn(true)->once();

        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('getText')->andReturn('/example');

        $result = $this->manager->find($message);
        $this->assertInstanceOf(Intent::class, $result);
        $this->assertSame($intent, $result);
    }

    public function test_finds_intent_by_activator_but_does_not_pass_authorization()
    {
        $this->manager->add($intent = $this->mock(Intent::class));

        $intent->shouldReceive('activators')->andReturn([$activator = $this->mock(Activator::class)])->once();
        $intent->shouldReceive('passesAuthorization')->andReturn(false)->once();
        $activator->shouldReceive('matches')->andReturn(true)->once();

        $message = $this->mock(ReceivedMessage::class);
        $message->shouldReceive('getText')->andReturn('/example');

        $result = $this->manager->find($message);
        $this->assertNull($result);
    }
}
