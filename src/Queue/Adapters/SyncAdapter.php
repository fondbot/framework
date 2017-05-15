<?php

declare(strict_types=1);

namespace FondBot\Queue\Adapters;

use FondBot\Queue\Adapter;
use FondBot\Drivers\Driver;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;

class SyncAdapter extends Adapter
{
    /**
     * Establish connection to the queue.
     */
    public function connect(): void
    {
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
        $driver->handle($command);
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
        if ($delay > 0) {
            sleep($delay);
        }

        $this->push($channel, $driver, $command);
    }
}
