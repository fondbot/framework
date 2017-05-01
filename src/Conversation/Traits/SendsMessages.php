<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Queue\Queue;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Application\Kernel;
use FondBot\Templates\Attachment;
use FondBot\Conversation\Template;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendRequest;
use FondBot\Drivers\Commands\SendAttachment;

trait SendsMessages
{
    /** @var Kernel */
    protected $kernel;

    /**
     * Send message to user.
     *
     * @param string        $text
     * @param Template|null $template
     */
    protected function sendMessage(string $text, Template $template = null): void
    {
        /** @var Queue $queue */
        $queue = $this->kernel->resolve(Queue::class);

        $command = new SendMessage($this->getChat(), $this->getUser(), $text, $template);
        $queue->push($this->kernel->getDriver(), $command);
    }

    /**
     * Send message to user with delay.
     *
     * @param int           $delay
     * @param string        $text
     * @param Template|null $template
     */
    protected function sendDelayedMessage(int $delay, string $text, Template $template = null): void
    {
        /** @var Queue $queue */
        $queue = $this->kernel->resolve(Queue::class);

        $command = new SendMessage($this->getChat(), $this->getUser(), $text, $template);
        $queue->later($this->kernel->getDriver(), $command, $delay);
    }

    /**
     * Send attachment to user.
     *
     * @param Attachment $attachment
     * @param int        $delay
     */
    protected function sendAttachment(Attachment $attachment, int $delay = 0): void
    {
        /** @var Queue $queue */
        $queue = $this->kernel->resolve(Queue::class);

        $command = new SendAttachment($this->getChat(), $this->getUser(), $attachment);

        if ($delay === 0) {
            $queue->push($this->kernel->getDriver(), $command);
        } else {
            $queue->later($this->kernel->getDriver(), $command, $delay);
        }
    }

    /**
     * Send request to the messaging service.
     *
     * @param string $endpoint
     * @param array  $parameters
     */
    protected function sendRequest(string $endpoint, array $parameters = []): void
    {
        /** @var Queue $queue */
        $queue = $this->kernel->resolve(Queue::class);

        $command = new SendRequest($this->getChat(), $this->getUser(), $endpoint, $parameters);
        $queue->push($this->kernel->getDriver(), $command);
    }

    /**
     * Get chat.
     *
     * @return Chat
     */
    abstract protected function getChat(): Chat;

    /**
     * Get user.
     *
     * @return User
     */
    abstract protected function getUser(): User;
}
