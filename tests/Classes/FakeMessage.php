<?php

declare(strict_types=1);

namespace Tests\Classes;

use Faker\Factory;
use FondBot\Contracts\Channels\Message;
use FondBot\Contracts\Channels\Message\Location;
use FondBot\Contracts\Channels\Message\Attachment;

class FakeMessage implements Message
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

    public static function create()
    {
        $faker = Factory::create();
        return new self(
            $faker->text,
            new Location($faker->latitude, $faker->longitude),
            new Attachment('image', $faker->imageUrl())
        );
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
}
