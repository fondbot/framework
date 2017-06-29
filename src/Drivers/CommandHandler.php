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

    /**
     * Handle send message command.
     *
     * @param SendMessage $command
     */
    abstract protected function handleSendMessage(SendMessage $command): void;

    /**
     * Handle send attachment command.
     *
     * @param SendAttachment $command
     */
    abstract protected function handleSendAttachment(SendAttachment $command): void;

    /**
     * Handle send request command.
     *
     * @param SendRequest $command
     */
    abstract protected function handleSendRequest(SendRequest $command): void;

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
