<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Queue\Adapters;

use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;
use FondBot\Drivers\AbstractDriver;
use FondBot\Queue\Adapters\SyncAdapter;

class SyncAdapterTest extends TestCase
{
    public function test_next(): void
    {
        $this->assertNull((new SyncAdapter())->next());
    }

    public function test_push(): void
    {
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(AbstractDriver::class);
        $command = $this->mock(Command::class);

        $driver->shouldReceive('handle')->with($command)->once();

        $adapter = new SyncAdapter();
        $adapter->push($channel, $driver, $command);
    }

    public function test_later(): void
    {
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(AbstractDriver::class);
        $command = $this->mock(Command::class);

        $driver->shouldReceive('handle')->with($command)->once();

        $adapter = new SyncAdapter();
        $adapter->later($channel, $driver, $command, 1);
    }
}
