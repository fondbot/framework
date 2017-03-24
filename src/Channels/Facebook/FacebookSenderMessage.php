<?php

declare(strict_types=1);

namespace FondBot\Channels\Facebook;

use FondBot\Contracts\Channels\SenderMessage;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

class FacebookSenderMessage implements SenderMessage
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
        return $this->payload['text'] ?? null;
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        if ($location = $this->getAttachmentPayload('location')) {
            return new Location(
                $location['coordinates']['lat'],
                $location['coordinates']['long']
            );
        }

        return null;
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return $this->getImage()
            ?? $this->getAudio()
            ?? $this->getVideo()
            ?? $this->getFile();
    }

    /**
     * Get image.
     *
     * @return Attachment|null
     */
    public function getImage(): ?Attachment
    {
        if ($image = $this->getAttachmentPayload('image')) {
            return new Attachment(Attachment::TYPE_IMAGE, $image['url']);
        }

        return null;
    }

    /**
     * Get audio.
     *
     * @return Attachment|null
     */
    public function getAudio(): ?Attachment
    {
        if ($audio = $this->getAttachmentPayload('audio')) {
            return new Attachment(Attachment::TYPE_AUDIO, $audio['url']);
        }

        return null;
    }

    /**
     * Get video.
     *
     * @return Attachment|null
     */
    public function getVideo(): ?Attachment
    {
        if ($video = $this->getAttachmentPayload('video')) {
            return new Attachment(Attachment::TYPE_VIDEO, $video['url']);
        }

        return null;
    }

    /**
     * Get file.
     *
     * @return Attachment|null
     */
    public function getFile(): ?Attachment
    {
        if ($file = $this->getAttachmentPayload('file')) {
            return new Attachment(Attachment::TYPE_FILE, $file['url']);
        }

        return null;
    }

    private function getAttachmentPayload(string $type): ?array
    {
        if (!$attachments = $this->payload['attachments'] ?? null) {
            return null;
        }

        // Is it real to send many locations or something in one request?
        return collect($attachments)->first(function ($attachment) use ($type) {
            return $attachment['type'] === $type;
        })['payload'] ?? null;
    }
}
