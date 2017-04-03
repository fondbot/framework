<?php

declare(strict_types=1);

namespace FondBot\Channels\VkCommunity;

use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

class VkCommunityReceivedMessage implements ReceivedMessage
{
    private $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->payload['body'] ?? null;
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
     * Determine if message has attachment.
     *
     * @return bool
     */
    public function hasAttachment(): bool
    {
        return false;
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

    /**
     * Determine if message has data payload.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        return false;
    }

    /**
     * Get data payload.
     *
     * @return string|null
     */
    public function getData(): ?string
    {
        return null;
    }
}
