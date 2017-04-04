<?php

declare(strict_types=1);

namespace Tests\Unit\Drivers;

use Tests\TestCase;
use FondBot\Drivers\Driver;
use FondBot\Channels\Channel;
use FondBot\Drivers\DriverManager;

class DriverManagerTest extends TestCase
{
    public function test_get()
    {
        $manager = new DriverManager();
        $manager->add('fake', $driver = $this->mock(Driver::class));

        $driver->shouldReceive('getConfig')->andReturn(['token'])->atLeast()->once();
        $driver->shouldReceive('fill')->with([], [], [])->once();

        $channel = new Channel('test', 'fake', ['token' => $this->faker()->sha1]);

        $driver = $manager->get($channel);

        $this->assertInstanceOf(Driver::class, $driver);
    }

    /**
     * @expectedException \FondBot\Drivers\Exceptions\InvalidConfiguration
     * @expectedExceptionMessage Invalid `test` channel configuration.
     */
    public function test_get_invalid_configuration()
    {
        $manager = new DriverManager();
        $manager->add('fake', $driver = $this->mock(Driver::class));

        $driver->shouldReceive('getConfig')->andReturn(['token'])->atLeast()->once();

        $channel = new Channel('test', 'fake', ['old' => $this->faker()->sha1]);

        $manager->get($channel);
    }
}
