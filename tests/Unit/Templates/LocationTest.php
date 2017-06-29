<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Templates;

use FondBot\Tests\TestCase;
use FondBot\Templates\Location;

class LocationTest extends TestCase
{
    public function test()
    {
        $location = (new Location)
            ->setLatitude($latitude = $this->faker()->latitude)
            ->setLongitude($longitude = $this->faker()->longitude);

        $this->assertSame($latitude, $location->getLatitude());
        $this->assertSame($longitude, $location->getLongitude());
    }
}
