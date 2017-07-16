<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Queue\Adapters;

use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;
use FondBot\Queue\Adapters\SyncAdapter;

class SyncAdapterTest extends TestCase
{
    public function testPull(): void
    {
        $this->assertNull((new SyncAdapter())->pull());
    }

    public function testPush(): void
    {
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $command = $this->mock(Command::class);

        $driver->shouldReceive('handle')->with($command)->once();

        $adapter = new SyncAdapter();
        $adapter->push($channel, $driver, $command);
    }

    public function testLater(): void
    {
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $command = $this->mock(Command::class);

        $driver->shouldReceive('handle')->with($command)->once();

        $adapter = new SyncAdapter();
        $adapter->later($channel, $driver, $command, 1);
    }
}
