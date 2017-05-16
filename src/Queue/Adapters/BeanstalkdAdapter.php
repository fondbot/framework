<?php

declare(strict_types=1);

namespace FondBot\Queue\Adapters;

use FondBot\Queue\Job;
use FondBot\Queue\Adapter;
use Pheanstalk\Pheanstalk;
use FondBot\Drivers\Driver;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;
use Pheanstalk\Job as PheanstalkJob;
use FondBot\Queue\SerializableForQueue;

class BeanstalkdAdapter extends Adapter
{
    /** @var Pheanstalk */
    private $connection;

    private $host;
    private $port;
    private $queue;
    private $timeout;
    private $persistent;

    public function __construct(
        string $host,
        int $port = 11300,
        string $queue = 'default',
        int $timeout = null,
        bool $persistent = false
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->queue = $queue;
        $this->timeout = $timeout;
        $this->persistent = $persistent;
    }

    /**
     * Establish connection to the queue.
     */
    public function connect(): void
    {
        $this->connection = new Pheanstalk($this->host, $this->port, $this->timeout, $this->persistent);
    }

    /**
     * Pull next job from the queue.
     *
     * @return Job|SerializableForQueue
     */
    public function next(): ?Job
    {
        $pheanstalkJob = $this->connection->watch($this->queue)->reserve();

        if ($pheanstalkJob instanceof PheanstalkJob) {
            $job = $this->unserialize($pheanstalkJob->getData());

            $this->connection->delete($pheanstalkJob);

            return $job;
        }

        return null;
    }

    /**
     * Push command onto the queue.
     *
     * @param Channel $channel
     * @param Driver  $driver
     * @param Command $command
     */
    public function push(Channel $channel, Driver $driver, Command $command): void
    {
        if ($this->connection === null) {
            $this->connect();
        }

        $job = new Job($channel, $driver, $command);
        $this->connection->putInTube($this->queue, $this->serialize($job));
    }

    /**
     * Push command onto the queue with a delay.
     *
     * @param Channel $channel
     * @param Driver  $driver
     * @param Command $command
     * @param int     $delay
     *
     * @return mixed|void
     */
    public function later(Channel $channel, Driver $driver, Command $command, int $delay): void
    {
        if ($this->connection === null) {
            $this->connect();
        }

        $job = new Job($channel, $driver, $command);
        $this->connection->putInTube($this->queue, $this->serialize($job), Pheanstalk::DEFAULT_PRIORITY, $delay);
    }
}
