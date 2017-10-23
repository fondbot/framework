<?php

declare(strict_types=1);

namespace FondBot\Templates;

use Illuminate\Support\Collection;

class Location
{
    private $latitude;
    private $longitude;
    private $parameters;

    public function __construct(float $latitude, float $longitude, array $parameters = [])
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->parameters = collect($parameters);
    }

    public static function create(float $latitude, float $longitude, array $parameters = [])
    {
        return new static($latitude, $longitude, $parameters);
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): Location
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): Location
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): Location
    {
        $this->parameters = collect($parameters);

        return $this;
    }
}
