<?php

declare(strict_types=1);

namespace FondBot\Contracts;

use FondBot\Drivers\Driver;
use FondBot\Drivers\Command;

interface Queue
{
    /**
     * Push command onto the queue.
     *
     * @param Driver  $driver
     * @param Command $command
     */
    public function push(Driver $driver, Command $command): void;

    /**
     * Push command onto the queue with a delay.
     *
     * @param Driver  $driver
     * @param Command $command
     * @param int     $delay
     *
     * @return mixed|void
     */
    public function later(Driver $driver, Command $command, int $delay): void;
}
