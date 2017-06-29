<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\DriverManager;

class DriverManagerTest extends TestCase
{
    public function test_get(): void
    {
        $driver = $this->mock(Driver::class);
        $manager = new DriverManager;

        $driver->shouldReceive('getShortName')->andReturn('foo')->once();

        $manager->add($driver);

        $this->assertSame($driver, $manager->get('foo'));
    }

    /**
     * @expectedException \FondBot\Drivers\Exceptions\DriverNotFound
     * @expectedExceptionMessage Driver `foo` not found.
     */
    public function test_get_driver_does_not_exist(): void
    {
        $manager = new DriverManager;

        $manager->get('foo');
    }
}
