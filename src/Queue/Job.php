<?php

declare(strict_types=1);

namespace FondBot\Queue;

use FondBot\Drivers\Driver;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;

class Job implements SerializableForQueue
{
    public $channel;
    public $driver;
    public $command;

    public function __construct(Channel $channel, Driver $driver, Command $command)
    {
        $this->channel = $channel;
        $this->driver = $driver;
        $this->command = $command;
    }
}
