<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Queue;

use FondBot\Tests\TestCase;
use FondBot\Contracts\Queue;
use FondBot\Queue\Adapters\SyncAdapter;
use FondBot\Queue\QueueServiceProvider;

class QueueServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(QueueServiceProvider::class)->makePartial();
        $provider->shouldReceive('adapter')->andReturn($adapter = new SyncAdapter)->once();

        $this->container->addServiceProvider($provider);

        $this->assertSame($adapter, $this->container->get(Queue::class));
    }
}
