<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

interface ReceivedMessage
{
    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText(): ?string;

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location;

    /**
     * Determine if message has attachment.
     *
     * @return bool
     */
    public function hasAttachment(): bool;

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment;
}
