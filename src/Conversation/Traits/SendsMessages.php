<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Queue\Queue;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Application\Kernel;
use FondBot\Conversation\Keyboard;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendAttachment;
use FondBot\Drivers\ReceivedMessage\Attachment;

trait SendsMessages
{
    /** @var Kernel */
    protected $kernel;

    /**
     * Send message to user.
     *
     * @param string        $text
     * @param Keyboard|null $keyboard
     */
    protected function sendMessage(string $text, Keyboard $keyboard = null): void
    {
        /** @var Queue $queue */
        $queue = $this->kernel->get(Queue::class);

        $command = new SendMessage($this->getChat(), $this->getUser(), $text, $keyboard);
        $queue->push($this->kernel->getDriver(), $command);
    }

    /**
     * Send message to user with delay.
     *
     * @param int           $delay
     * @param string        $text
     * @param Keyboard|null $keyboard
     */
    protected function sendDelayedMessage(int $delay, string $text, Keyboard $keyboard = null): void
    {
        /** @var Queue $queue */
        $queue = $this->kernel->get(Queue::class);

        $command = new SendMessage($this->getChat(), $this->getUser(), $text, $keyboard);
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
        $queue = $this->kernel->get(Queue::class);

        $command = new SendAttachment($this->getChat(), $this->getUser(), $attachment);

        if ($delay === 0) {
            $queue->push($this->kernel->getDriver(), $command);
        } else {
            $queue->later($this->kernel->getDriver(), $command, $delay);
        }
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
