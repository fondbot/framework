<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use Faker\Generator;
use FondBot\Drivers\Message\Location;
use FondBot\Drivers\Message\Attachment;
use FondBot\Contracts\Channels\ReceivedMessage;

class FakeReceivedMessage implements ReceivedMessage
{
    private $faker;
    private $text;
    private $location;
    private $attachment;

    public function __construct(
        Generator $faker = null,
        string $text = null,
        Location $location = null,
        Attachment $attachment = null
    ) {
        $this->faker = $faker;
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
        return $this->location ?? new Location($this->faker->latitude, $this->faker->longitude);
    }

    /**
     * Determine if message has attachment.
     *
     * @return bool
     */
    public function hasAttachment(): bool
    {
        return $this->attachment !== null;
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return $this->attachment ?? new Attachment('image', $this->faker->imageUrl());
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
