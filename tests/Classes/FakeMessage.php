<?php

declare(strict_types=1);

namespace Tests\Classes;

use Faker\Factory;
use Faker\Generator;
use FondBot\Contracts\Channels\Message;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

class FakeMessage implements Message
{
    /**
     * Get text.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->faker()->text;
    }

    /**
     * Get location.
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return new Location($this->faker()->latitude, $this->faker()->longitude);
    }

    /**
     * Get attachment.
     *
     * @return Attachment|null
     */
    public function getAttachment(): ?Attachment
    {
        return new Attachment('image', $this->faker()->imageUrl());
    }

    private function faker(): Generator
    {
        return Factory::create();
    }
}
