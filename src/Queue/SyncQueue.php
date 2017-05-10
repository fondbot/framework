<?php

declare(strict_types=1);

namespace FondBot\Queue;

use FondBot\Drivers\Driver;
use FondBot\Contracts\Queue;
use FondBot\Drivers\Command;

class SyncQueue implements Queue
{
    /**
     * Push command onto the queue.
     *
     * @param Driver  $driver
     * @param Command $command
     */
    public function push(Driver $driver, Command $command): void
    {
        $driver->handle($command);
    }

    /**
     * Push command onto the queue with a delay.
     *
     * @param Driver  $driver
     * @param Command $command
     * @param int     $delay
     *
     * @return mixed|void
     */
    public function later(Driver $driver, Command $command, int $delay): void
    {
        if ($delay > 0) {
            sleep($delay);
        }

        $this->push($driver, $command);
    }
}
