<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Queue\Queue;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Application\Kernel;
use FondBot\Conversation\Context;
use FondBot\Conversation\FallbackIntent;

/**
 * @property FallbackIntent $intent
 */
class FallbackIntentTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->intent = new FallbackIntent;
    }

    public function test_activators()
    {
        $this->assertSame([], $this->intent->activators());
    }

    public function test_run()
    {
        $kernel = $this->mock(Kernel::class);
        $queue = $this->mock(Queue::class);
        $driver = $this->mock(Driver::class);
        $context = $this->mock(Context::class);
        $chat = $this->mock(Chat::class);
        $user = $this->mock(User::class);

        $kernel->shouldReceive('resolve')->with(Queue::class)->andReturn($queue)->once();
        $kernel->shouldReceive('getDriver')->andReturn($driver)->once();
        $kernel->shouldReceive('getContext')->andReturn($context)->atLeast()->once();
        $context->shouldReceive('getChat')->andReturn($chat)->atLeast()->once();
        $context->shouldReceive('getUser')->andReturn($user)->atLeast()->once();

        $queue->shouldReceive('push')->once();

        $this->intent->handle($kernel);
    }
}
