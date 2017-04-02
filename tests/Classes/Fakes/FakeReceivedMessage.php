<?php

declare(strict_types=1);

namespace Tests\Classes\Fakes;

use Faker\Generator;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

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
}
