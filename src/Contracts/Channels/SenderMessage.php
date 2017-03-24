<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

/**
 * Message received from sender.
 */
interface SenderMessage
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
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment;
}
