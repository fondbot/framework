<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels;

use FondBot\Contracts\Channels\Message\Location;

class Message
{
    /** @var string */
    protected $text;

    /** @var Location */
    protected $location;

    public static function create(string $text, Location $location = null): Message
    {
        $instance = new self;
        $instance->setText($text);
        $instance->setLocation($location);

        return $instance;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }
}
