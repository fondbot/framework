<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Intent;
use FondBot\Events\MessageReceived;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\FallbackIntent;
use FondBot\Conversation\Activators\Activator;

/**
 * @property IntentManager manager
 */
class IntentManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->markTestIncomplete();

        $this->manager = new IntentManager;
    }

    public function testReturnsFallbackIntentIfNoIntentFound(): void
    {
        $this->manager->register([$intent = $this->mock(Intent::class)], FallbackIntent::class);

        $intent->shouldReceive('activators')->andReturn([$activator = $this->mock(Activator::class)])->once();
        $activator->shouldReceive('matches')->andReturn(false)->once();

        $message = $this->mock(MessageReceived::class);

        $result = $this->manager->find($message);
        $this->assertInstanceOf(FallbackIntent::class, $result);
    }

    public function testFindsIntentByActivator(): void
    {
        $this->manager->register([$intent = $this->mock(Intent::class)], FallbackIntent::class);

        $intent->shouldReceive('activators')->andReturn([$activator = $this->mock(Activator::class)])->once();
        $intent->shouldReceive('passesAuthorization')->andReturn(true)->once();
        $activator->shouldReceive('matches')->andReturn(true)->once();

        $message = $this->mock(MessageReceived::class);
        $message->shouldReceive('getText')->andReturn('/example');

        $result = $this->manager->find($message);
        $this->assertInstanceOf(Intent::class, $result);
        $this->assertSame($intent, $result);
    }

    public function testFindsIntentByActivatorButDoesNotPassAuthorization(): void
    {
        $this->manager->register([$intent = $this->mock(Intent::class)], FallbackIntent::class);

        $intent->shouldReceive('activators')->andReturn([$activator = $this->mock(Activator::class)])->once();
        $intent->shouldReceive('passesAuthorization')->andReturn(false)->once();
        $activator->shouldReceive('matches')->andReturn(true)->once();

        $message = $this->mock(MessageReceived::class);
        $message->shouldReceive('getText')->andReturn('/example');

        $result = $this->manager->find($message);
        $this->assertNull($result);
    }
}
