<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

class FakeReceivedMessage implements ReceivedMessage
{
    protected $text;
    protected $location;
    protected $attachment;

    public function __construct(string $text, ?Location $location = null, ?Attachment $attachment = null)
    {
        $this->text = $text;
        $this->location = $location;
        $this->attachment = $attachment;
    }

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return $this->attachment;
    }

    public function withoutLocation(): void
    {
        $this->location = null;
    }

    public function withoutAttachment(): void
    {
        $this->attachment = null;
    }
}
