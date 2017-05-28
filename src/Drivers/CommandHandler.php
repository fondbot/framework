<?php

declare(strict_types=1);

namespace FondBot\Drivers;

use RuntimeException;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendRequest;
use FondBot\Drivers\Commands\SendAttachment;

abstract class CommandHandler
{
    protected $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    abstract public function handleSendMessage(SendMessage $command): void;

    abstract public function handleSendAttachment(SendAttachment $command): void;

    abstract public function handleSendRequest(SendRequest $command): void;

    /**
     * @param Command $command
     *
     * @throws RuntimeException
     */
    public function handle(Command $command): void
    {
        $method = 'handle'.ucfirst($command->getName());

        if (!method_exists($this, $method)) {
            throw new RuntimeException('No handle method for "'.$command->getName().'".');
        }

        $this->$method($command);
    }
}
