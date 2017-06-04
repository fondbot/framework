<?php

declare(strict_types=1);

namespace FondBot\Queue;

use FondBot\Drivers\Command;
use FondBot\Channels\Channel;
use FondBot\Drivers\AbstractDriver;

class Job implements SerializableForQueue
{
    public $channel;
    public $driver;
    public $command;

    public function __construct(Channel $channel, AbstractDriver $driver, Command $command)
    {
        $this->channel = $channel;
        $this->driver = $driver;
        $this->command = $command;
    }
}
