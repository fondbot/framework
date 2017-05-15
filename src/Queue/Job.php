<?php

declare(strict_types=1);

namespace FondBot\Queue;

use FondBot\Channels\Channel;
use FondBot\Drivers\Command;
use FondBot\Drivers\Driver;

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
