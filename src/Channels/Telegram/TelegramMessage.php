<?php

declare(strict_types=1);

namespace FondBot\Channels\Telegram;

use FondBot\Contracts\Channels\Message;
use FondBot\Contracts\Channels\Message\Attachment;
use FondBot\Contracts\Channels\Message\Location;
use GuzzleHttp\Client;

class TelegramMessage implements Message
{
    private $baseUrl;
    private $payload;

    public function __construct(string $baseUrl, array $payload)
    {
        $this->baseUrl = $baseUrl;
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
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return
            $this->getAudio() ??
            $this->getDocument() ??
            $this->getSticker() ??
            $this->getVideo() ??
            $this->getVoice();
    }

    /**
     * Get audio.
     *
     * @return Attachment|null
     */
    public function getAudio(): ?Attachment
    {
        if (!isset($this->payload['audio'])) {
            return null;
        }

        return new Attachment(
            'audio',
            $this->getFilePath($this->payload['audio']['file_id'])
        );
    }

    /**
     * Get document.
     *
     * @return Attachment|null
     */
    public function getDocument(): ?Attachment
    {
        if (!isset($this->payload['document'])) {
            return null;
        }

        return new Attachment(
            'document',
            $this->getFilePath($this->payload['document']['file_id'])
        );
    }

    /**
     * Get sticker.
     *
     * @return Attachment|null
     */
    public function getSticker(): ?Attachment
    {
        if (!isset($this->payload['sticker'])) {
            return null;
        }

        return new Attachment(
            'sticker',
            $this->getFilePath($this->payload['sticker']['file_id'])
        );
    }

    /**
     * Get video.
     *
     * @return Attachment|null
     */
    public function getVideo(): ?Attachment
    {
        if (!isset($this->payload['video'])) {
            return null;
        }

        return new Attachment(
            'video',
            $this->getFilePath($this->payload['video']['file_id'])
        );
    }

    /**
     * Get voice.
     *
     * @return Attachment|null
     */
    public function getVoice(): ?Attachment
    {
        if (!isset($this->payload['voice'])) {
            return null;
        }

        return new Attachment(
            'voice',
            $this->getFilePath($this->payload['voice']['file_id'])
        );
    }

    /**
     * Get contact.
     *
     * @return array|null
     */
    public function getContact(): ?array
    {
        if (!isset($this->payload['contact'])) {
            return null;
        }

        $contact = $this->payload['contact'];

        $phoneNumber = $contact['phone_number'];
        $firstName = $contact['first_name'];
        $lastName = $contact['last_name'] ?? null;
        $userId = $contact['user_id'] ?? null;

        return [
            'phone_number' => $phoneNumber,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'user_id' => $userId,
        ];
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        if (!isset($this->payload['location'])) {
            return null;
        }

        return new Location(
            $this->payload['location']['latitude'],
            $this->payload['location']['longitude']
        );
    }

    /**
     * Get venue.
     *
     * @return array|null
     */
    public function getVenue(): ?array
    {
        if (!isset($this->payload['venue'])) {
            return null;
        }

        $venue = $this->payload['venue'];
        $location = new Location(
            $this->payload['venue']['location']['latitude'],
            $this->payload['venue']['location']['longitude']
        );

        $title = $venue['title'];
        $address = $venue['address'];
        $foursquareId = $venue['foursquare_id'] ?? null;

        return [
            'location' => $location,
            'title' => $title,
            'address' => $address,
            'foursquare_id' => $foursquareId,
        ];
    }

    /**
     * Get file path.
     *
     * @param string $fileId
     *
     * @return string
     */
    private function getFilePath(string $fileId): string
    {
        $response = $this->guzzle()->post($this->baseUrl.'/getFile', [
            'form_params' => [
                'file_id' => $fileId,
            ],
        ]);

        $response = $response->getBody()->getContents();
        $response = json_decode($response, true);

        return $this->baseUrl.'/'.$response['file_path'];
    }

    private function guzzle(): Client
    {
        return resolve(Client::class);
    }
}
