<?php

declare(strict_types=1);

namespace FondBot\Contracts\Channels\Message;

class Location
{
    protected $latitude;
    protected $longitude;

    public static function create(float $latitude, float $longitude): Location
    {
        $instance = new self;
        $instance->setLatitude($latitude);
        $instance->setLongitude($longitude);

        return $instance;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }
}
