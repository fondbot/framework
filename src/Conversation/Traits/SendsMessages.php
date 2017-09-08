<?php

declare(strict_types=1);

namespace FondBot\Conversation\Traits;

use FondBot\Contracts\Template;
use FondBot\Templates\Attachment;
use FondBot\Foundation\Commands\SendMessage;
use FondBot\Foundation\Commands\SendRequest;
use FondBot\Foundation\Commands\SendAttachment;

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
        SendMessage::dispatch(
            context()->getChannel(),
            context()->getChat(),
            context()->getUser(),
            $text,
            $template
        )->delay($delay);
    }

    /**
     * Send attachment to user.
     *
     * @param Attachment $attachment
     * @param int        $delay
     */
    protected function sendAttachment(Attachment $attachment, int $delay = 0): void
    {
        SendAttachment::dispatch(
            context()->getChannel(),
            context()->getChat(),
            context()->getUser(),
            $attachment
        )->delay($delay);
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
        SendRequest::dispatch(
            context()->getChannel(),
            context()->getChat(),
            context()->getUser(),
            $endpoint,
            $parameters
        )->delay($delay);
    }
}
