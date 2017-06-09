<?php

declare(strict_types=1);

namespace FondBot\Contracts;

use FondBot\Queue\Job;
use FondBot\Drivers\Driver;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;

interface Queue
{
    /**
     * Pull next job from the queue.
     *
     * @return Job
     */
    public function pull(): ?Job;

    /**
     * Push command onto the queue.
     *
     * @param Channel $channel
     * @param Driver  $driver
     * @param Command $command
     */
    public function push(Channel $channel, Driver $driver, Command $command): void;

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
    public function later(Channel $channel, Driver $driver, Command $command, int $delay): void;
}
