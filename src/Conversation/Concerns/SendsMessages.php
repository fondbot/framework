<?php

declare(strict_types=1);

namespace FondBot\Conversation\Concerns;

use FondBot\Drivers\PendingReply;
use FondBot\Templates\Attachment;
use FondBot\Drivers\PendingRequest;

trait SendsMessages
{
    /**
     * Send reply to user.
     *
     * @param string|null $text
     *
     * @return PendingReply
     */
    protected function reply(string $text = null): PendingReply
    {
        return (new PendingReply(
            context()->getChannel(),
            context()->getChat(),
            context()->getUser()
        ))->text($text);
    }

    /**
     * Send attachment to user.
     *
     * @param Attachment $attachment
     *
     * @return PendingReply
     */
    protected function sendAttachment(Attachment $attachment): PendingReply
    {
        return (new PendingReply(
            context()->getChannel(),
            context()->getChat(),
            context()->getUser()
        ))->attachment($attachment);
    }

    /**
     * Send request to the messaging service.
     *
     * @param string $endpoint
     * @param array $parameters
     *
     * @return PendingRequest
     */
    protected function sendRequest(string $endpoint, array $parameters = []): PendingRequest
    {
        return (new PendingRequest(
            context()->getChannel(),
            context()->getChat(),
            context()->getUser()
        ))->endpoint($endpoint)->parameters($parameters);
    }
}
