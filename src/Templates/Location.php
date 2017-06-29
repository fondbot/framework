<?php

declare(strict_types=1);

namespace FondBot\Templates;

class Location
{
    private $latitude;
    private $longitude;

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
     * Set latitude.
     *
     * @param float $latitude
     *
     * @return Location
     */
    public function setLatitude(float $latitude): Location
    {
        $this->latitude = $latitude;

        return $this;
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

    /**
     * Set longitude.
     *
     * @param float $longitude
     *
     * @return Location
     */
    public function setLongitude(float $longitude): Location
    {
        $this->longitude = $longitude;

        return $this;
    }
}
