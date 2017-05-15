<?php

declare(strict_types=1);

namespace Tests\Unit\Queue\Adapters;

use FondBot\Channels\Channel;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use FondBot\Queue\Adapters\SyncAdapter;

class SyncAdapterTest extends TestCase
{
    public function test_push(): void
    {
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $command = $this->mock(Command::class);
        $queue = new SyncAdapter();

        $driver->shouldReceive('handle')->with($command)->once();

        $queue->push($channel, $driver, $command);
    }

    public function test_later(): void
    {
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $command = $this->mock(Command::class);
        $queue = new SyncAdapter();

        $driver->shouldReceive('handle')->with($command)->once();

        $queue->later($channel, $driver, $command, 1);
    }
}
