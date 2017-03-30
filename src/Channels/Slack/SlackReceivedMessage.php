<?php

declare(strict_types = 1);

namespace FondBot\Channels\Slack;

use GuzzleHttp\Client;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

class SlackReceivedMessage implements ReceivedMessage
{
    private $guzzle;
    private $token;
    private $payload;

    public function __construct(Client $guzzle, string $token, array $payload)
    {
        $this->guzzle  = $guzzle;
        $this->token   = $token;
        $this->payload = $payload;
    }

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->payload['text'] ?? null;
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return null;
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return null;
    }
}