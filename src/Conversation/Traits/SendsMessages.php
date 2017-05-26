<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Contracts\Queue;
use FondBot\Templates\Attachment;
use FondBot\Conversation\Template;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendRequest;
use FondBot\Drivers\Commands\SendAttachment;

trait SendsMessages
{
    /**
     * Send message to user.
     *
     * @param string        $text
     * @param Template|null $template
     * @param int           $delay
     */
    protected function sendMessage(string $text, Template $template = null, int $delay = 0): void
    {
        /** @var Queue $queue */
        $queue = resolve(Queue::class);

        $command = new SendMessage($this->getChat(), $this->getUser(), $text, $template);

        if ($delay > 0) {
            $queue->later(kernel()->getChannel(), kernel()->getDriver(), $command, $delay);
        } else {
            $queue->push(kernel()->getChannel(), kernel()->getDriver(), $command);
        }
    }

    /**
     * Send attachment to user.
     *
     * @param Attachment $attachment
     * @param int        $delay
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    protected function sendAttachment(Attachment $attachment, int $delay = 0): void
    {
        /** @var Queue $queue */
        $queue = resolve(Queue::class);

        $command = new SendAttachment($this->getChat(), $this->getUser(), $attachment);

        if ($delay === 0) {
            $queue->push(kernel()->getChannel(), kernel()->getDriver(), $command);
        } else {
            $queue->later(kernel()->getChannel(), kernel()->getDriver(), $command, $delay);
        }
    }

    /**
     * Send request to the messaging service.
     *
     * @param string $endpoint
     * @param array  $parameters
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    protected function sendRequest(string $endpoint, array $parameters = []): void
    {
        /** @var Queue $queue */
        $queue = resolve(Queue::class);

        $command = new SendRequest($this->getChat(), $this->getUser(), $endpoint, $parameters);
        $queue->push(kernel()->getChannel(), kernel()->getDriver(), $command);
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
