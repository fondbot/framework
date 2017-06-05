<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt\Commands;

use FondBot\Queue\Job;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Queue;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;
use FondBot\Toolbelt\Commands\QueueWorker;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class QueueWorkerTest extends TestCase
{
    public function test_jobs_limit(): void
    {
        $queue = $this->mock(Queue::class);
        $job = new Job(
            $channel = $this->mock(Channel::class),
            $driver = $this->mock(Driver::class),
            $command = $this->mock(Command::class)
        );

        $this->container->add(Queue::class, $queue);

        $queue->shouldReceive('next')->andReturn($job)->once();
        $queue->shouldReceive('next')->andReturn(null)->once();
        $queue->shouldReceive('next')->andReturn($job)->once();
        $driver->shouldReceive('handle')->with($command)->twice();

        $application = new Application;
        $application->add(new QueueWorker);

        $command = $application->find('queue:worker');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), '--timeout' => 1, '--jobs' => 2]);

        $expected = 'Worker started...'.PHP_EOL;
        $expected .= 'Job: FondBot\Queue\Job'.PHP_EOL;
        $expected .= 'Job: FondBot\Queue\Job';

        $this->assertSame($expected, trim($commandTester->getDisplay(true)));
    }
}
