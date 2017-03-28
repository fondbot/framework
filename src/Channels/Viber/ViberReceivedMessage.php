<?php

declare(strict_types=1);

namespace FondBot\Channels\Viber;

use FondBot\Contracts\Channels\Message\Attachment;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\ReceivedMessage;

class ViberReceivedMessage implements ReceivedMessage
{

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        // TODO: Implement getText() method.
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        // TODO: Implement getLocation() method.
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        // TODO: Implement getAttachment() method.
    }
}