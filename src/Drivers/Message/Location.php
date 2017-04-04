<?php

declare(strict_types=1);

namespace FondBot\Drivers\Message;

use FondBot\Contracts\Drivers\Message\Location as LocationContract;

class Location implements LocationContract
{
    protected $latitude;
    protected $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
