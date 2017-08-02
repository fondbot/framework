<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\DriverManager;

class DriverManagerTest extends TestCase
{
    public function testGet(): void
    {
        $driver = $this->mock(Driver::class);
        $manager = new DriverManager;

        $driver->shouldReceive('getShortName')->andReturn('foo')->once();

        $manager->register($driver);

        $this->assertSame($driver, $manager->get('foo'));
    }

    /**
     * @expectedException \FondBot\Drivers\Exceptions\DriverNotFound
     * @expectedExceptionMessage Driver `foo` not found.
     */
    public function testGetDriverDoesNotExist(): void
    {
        $manager = new DriverManager;

        $manager->get('foo');
    }

    public function testAll(): void
    {
        $manager = new DriverManager;
        /** @var mixed $driver1 */
        $driver1 = $this->mock(Driver::class)->makePartial();
        /** @var mixed $driver2 */
        $driver2 = $this->mock(Driver::class)->makePartial();

        $manager->register($driver1);
        $manager->register($driver2);

        $this->assertCount(1, $manager->all());
    }
}
