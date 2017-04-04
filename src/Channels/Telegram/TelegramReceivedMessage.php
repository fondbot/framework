<?php

declare(strict_types=1);

namespace FondBot\Channels\Telegram;

use GuzzleHttp\Client;
use FondBot\Helpers\Arr;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

class TelegramReceivedMessage implements ReceivedMessage
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
        if (Arr::has($this->payload, ['callback_query'])) {
            return Arr::get($this->payload, 'callback_query.message.text');
        }

        return $this->payload['message']['text'] ?? null;
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        if (!isset($this->payload['message']['location'])) {
            return null;
        }

        return new Location(
            $this->payload['message']['location']['latitude'],
            $this->payload['message']['location']['longitude']
        );
    }

    /**
     * Determine if message has attachment.
     *
     * @return bool
     */
    public function hasAttachment(): bool
    {
        return collect($this->payload['message'])
                ->keys()
                ->intersect([
                    'audio',
                    'document',
                    'photo',
                    'sticker',
                    'video',
                    'voice',
                ])
                ->count() > 0;
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
            $this->getPhoto() ??
            $this->getSticker() ??
            $this->getVideo() ??
            $this->getVoice();
    }

    /**
     * Determine if message has data payload.
     *
     * @return bool
     */
    public function hasData(): bool
    {
        return Arr::has($this->payload, ['callback_query']);
    }

    /**
     * Get data payload.
     *
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->hasData() ? Arr::get($this->payload, 'callback_query.data') : null;
    }

    /**
     * Get audio.
     *
     * @return Attachment|null
     */
    public function getAudio(): ?Attachment
    {
        if (!isset($this->payload['message']['audio'])) {
            return null;
        }

        return new Attachment(
            Attachment::TYPE_AUDIO,
            $this->getFilePath($this->payload['message']['audio']['file_id']),
            $this->guzzle
        );
    }

    /**
     * Get document.
     *
     * @return Attachment|null
     */
    public function getDocument(): ?Attachment
    {
        if (!isset($this->payload['message']['document'])) {
            return null;
        }

        return new Attachment(
            'document',
            $this->getFilePath($this->payload['message']['document']['file_id']),
            $this->guzzle
        );
    }

    /**
     * Get photo.
     *
     * @return Attachment|null
     */
    public function getPhoto(): ?Attachment
    {
        if (!isset($this->payload['message']['photo'])) {
            return null;
        }

        /** @var array $photo */
        $photo = collect($this->payload['message']['photo'])->sortByDesc('file_size')->first();

        return new Attachment(
            'photo',
            $this->getFilePath($photo['file_id']),
            $this->guzzle
        );
    }

    /**
     * Get sticker.
     *
     * @return Attachment|null
     */
    public function getSticker(): ?Attachment
    {
        if (!isset($this->payload['message']['sticker'])) {
            return null;
        }

        return new Attachment(
            'sticker',
            $this->getFilePath($this->payload['message']['sticker']['file_id']),
            $this->guzzle
        );
    }

    /**
     * Get video.
     *
     * @return Attachment|null
     */
    public function getVideo(): ?Attachment
    {
        if (!isset($this->payload['message']['video'])) {
            return null;
        }

        return new Attachment(
            Attachment::TYPE_VIDEO,
            $this->getFilePath($this->payload['message']['video']['file_id']),
            $this->guzzle
        );
    }

    /**
     * Get voice.
     *
     * @return Attachment|null
     */
    public function getVoice(): ?Attachment
    {
        if (!isset($this->payload['message']['voice'])) {
            return null;
        }

        return new Attachment(
            'voice',
            $this->getFilePath($this->payload['message']['voice']['file_id']),
            $this->guzzle
        );
    }

    /**
     * Get contact.
     *
     * @return array|null
     */
    public function getContact(): ?array
    {
        if (!isset($this->payload['message']['contact'])) {
            return null;
        }

        $contact = $this->payload['message']['contact'];

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
     * Get venue.
     *
     * @return array|null
     */
    public function getVenue(): ?array
    {
        if (!isset($this->payload['message']['venue'])) {
            return null;
        }

        $venue = $this->payload['message']['venue'];
        $location = new Location(
            $this->payload['message']['venue']['location']['latitude'],
            $this->payload['message']['venue']['location']['longitude']
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
     * Get file path by id.
     *
     * @param string $fileId
     *
     * @return string
     */
    private function getFilePath(string $fileId): string
    {
        $response = $this->guzzle->post(
            'https://api.telegram.org/bot'.$this->token.'/getFile',
            [
                'form_params' => [
                    'file_id' => $fileId,
                ],
            ]
        );

        $response = $response->getBody()->getContents();
        $response = json_decode($response, true);

        return 'https://api.telegram.org/file/bot'.$this->token.'/'.$response['result']['file_path'];
    }
}
