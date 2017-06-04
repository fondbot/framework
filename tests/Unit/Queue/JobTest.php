<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Queue;

use FondBot\Queue\Job;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;
use FondBot\Drivers\AbstractDriver;

class JobTest extends TestCase
{
    public function test(): void
    {
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(AbstractDriver::class);
        $command = $this->mock(Command::class);

        $job = new Job($channel, $driver, $command);

        $this->assertSame($job->channel, $channel);
        $this->assertSame($job->driver, $driver);
        $this->assertSame($job->command, $command);
    }
}
