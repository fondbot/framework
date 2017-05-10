<?php

declare(strict_types=1);

namespace Tests\Unit\Queue;

use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use FondBot\Queue\SyncQueue;

class SyncQueueTest extends TestCase
{
    public function test_push(): void
    {
        $driver = $this->mock(Driver::class);
        $command = $this->mock(Command::class);
        $queue = new SyncQueue();

        $driver->shouldReceive('handle')->with($command)->once();

        $queue->push($driver, $command);
    }

    public function test_later(): void
    {
        $driver = $this->mock(Driver::class);
        $command = $this->mock(Command::class);
        $queue = new SyncQueue();

        $driver->shouldReceive('handle')->with($command)->once();

        $queue->later($driver, $command, 1);
    }
}
