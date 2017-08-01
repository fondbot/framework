<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Contracts\Template;
use FondBot\Templates\Attachment;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendRequest;
use FondBot\Drivers\Commands\SendAttachment;

trait SendsMessages
{
    /**
     * Send message to user.
     *
     * @param string|null   $text
     * @param Template|null $template
     * @param int           $delay
     */
    protected function sendMessage(string $text = null, Template $template = null, int $delay = 0): void
    {
        $command = new SendMessage($this->getChat(), $this->getUser(), $text, $template);

        kernel()->dispatch($command);
    }

    /**
     * Send attachment to user.
     *
     * @param Attachment $attachment
     * @param int        $delay
     */
    protected function sendAttachment(Attachment $attachment, int $delay = 0): void
    {
        $command = new SendAttachment($this->getChat(), $this->getUser(), $attachment);

        kernel()->dispatch($command);
    }

    /**
     * Send request to the messaging service.
     *
     * @param string $endpoint
     * @param array  $parameters
     * @param int    $delay
     */
    protected function sendRequest(string $endpoint, array $parameters = [], int $delay = 0): void
    {
        $command = new SendRequest($this->getChat(), $this->getUser(), $endpoint, $parameters);

        kernel()->dispatch($command);
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
