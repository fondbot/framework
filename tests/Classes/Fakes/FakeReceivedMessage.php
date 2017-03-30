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

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->faker->text;
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return new Location($this->faker->latitude, $this->faker->longitude);
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return new Attachment('image', $this->faker->imageUrl());
    }
}
