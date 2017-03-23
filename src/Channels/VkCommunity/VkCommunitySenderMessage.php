<?php

declare(strict_types=1);

namespace FondBot\Channels\VkCommunity;

use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

class VkCommunitySenderMessage implements SenderMessage
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
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return null;
    }
}
