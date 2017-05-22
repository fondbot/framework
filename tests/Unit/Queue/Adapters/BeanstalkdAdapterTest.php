<?php

declare(strict_types=1);

namespace Tests\Unit\Queue\Adapters;

use FondBot\Queue\Job;
use Pheanstalk\Pheanstalk;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use SuperClosure\Serializer;
use FondBot\Channels\Channel;
use Pheanstalk\Job as PheanstalkJob;
use Zumba\JsonSerializer\JsonSerializer;
use FondBot\Queue\Adapters\BeanstalkdAdapter;

class BeanstalkdAdapterTest extends TestCase
{
    public function test_next_returns_job(): void
    {
        $pheanstalk = $this->mock(Pheanstalk::class);
        $pheanstalkJob = $this->mock(PheanstalkJob::class);
        $job = new class extends Job {
            public function __construct()
            {
            }
        };

        $queue = $this->faker()->word;

        $serializer = new JsonSerializer(new Serializer);

        $pheanstalk->shouldReceive('watch')->with($queue)->andReturnSelf()->once();
        $pheanstalk->shouldReceive('reserve')->andReturn($pheanstalkJob)->once();
        $pheanstalkJob->shouldReceive('getData')->andReturn($serializer->serialize($job))->once();
        $pheanstalk->shouldReceive('delete')->with($pheanstalkJob);

        $adapter = new BeanstalkdAdapter($pheanstalk, $queue);
        $result = $adapter->next();

        $this->assertInstanceOf(Job::class, $result);
        $this->assertEquals($job, $result);
    }

    public function test_next_returns_null(): void
    {
        $pheanstalk = $this->mock(Pheanstalk::class);

        $pheanstalk->shouldReceive('watch')->with('default')->andReturnSelf()->once();
        $pheanstalk->shouldReceive('reserve')->andReturnNull()->once();

        $adapter = new BeanstalkdAdapter($pheanstalk);

        $this->assertNull($adapter->next());
    }

    public function test_push(): void
    {
        $pheanstalk = $this->mock(Pheanstalk::class);

        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $command = $this->mock(Command::class);
        $queue = $this->faker()->word;

        $pheanstalk->shouldReceive('putInTube')->once();

        $adapter = new BeanstalkdAdapter($pheanstalk, $queue);
        $adapter->push($channel, $driver, $command);
    }

    public function test_later(): void
    {
        $pheanstalk = $this->mock(Pheanstalk::class);

        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $command = $this->mock(Command::class);
        $queue = $this->faker()->word;
        $delay = random_int(1, 10);

        $pheanstalk->shouldReceive('putInTube')->once();

        $adapter = new BeanstalkdAdapter($pheanstalk, $queue);
        $adapter->later($channel, $driver, $command, $delay);
    }
}
