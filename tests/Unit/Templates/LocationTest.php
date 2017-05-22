<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Templates;

use FondBot\Tests\TestCase;
use FondBot\Templates\Location;

class LocationTest extends TestCase
{
    public function test()
    {
        $location = new Location($latitude = $this->faker()->latitude, $longitude = $this->faker()->longitude);

        $array = ['latitude' => $latitude, 'longitude' => $longitude];

        $this->assertSame($latitude, $location->getLatitude());
        $this->assertSame($longitude, $location->getLongitude());
        $this->assertSame($array, $location->toArray());
        $this->assertSame(json_encode($array), $location->jsonSerialize());
    }
}
