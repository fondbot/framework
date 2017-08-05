<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Jobs\SendMessage;
use FondBot\Jobs\SendRequest;
use FondBot\Contracts\Template;
use FondBot\Jobs\SendAttachment;
use FondBot\Templates\Attachment;

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
        kernel()->dispatch(
            (new SendMessage($this->getChat(), $this->getUser(), $text, $template))->delay($delay)
        );
    }

    /**
     * Send attachment to user.
     *
     * @param Attachment $attachment
     * @param int        $delay
     */
    protected function sendAttachment(Attachment $attachment, int $delay = 0): void
    {
        kernel()->dispatch(
            (new SendAttachment($this->getChat(), $this->getUser(), $attachment))->delay($delay)
        );
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
        kernel()->dispatch(
            (new SendRequest($this->getChat(), $this->getUser(), $endpoint, $parameters))->delay($delay)
        );
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
