<?php

declare(strict_types=1);

namespace Tests\Unit\Drivers;

use Tests\TestCase;
use FondBot\Drivers\Driver;
use FondBot\Channels\Channel;
use FondBot\Contracts\Container;
use FondBot\Drivers\DriverManager;
use TheCodingMachine\Discovery\Asset;
use TheCodingMachine\Discovery\Discovery;
use TheCodingMachine\Discovery\ImmutableAssetType;

class DriverManagerTest extends TestCase
{
    public function test_get()
    {
        $container = $this->mock(Container::class);
        $discovery = $this->mock(Discovery::class);

        $discovery->shouldReceive('getAssetType')
            ->with(Driver::class)
            ->andReturn(new ImmutableAssetType(Driver::class, [
                new Asset(Driver::class, '', '', 0, ['name' => 'fake']),
            ]))
            ->once();

        $container->shouldReceive('make')->with(Driver::class)->andReturn($driver = $this->mock(Driver::class))->once();

        $manager = new DriverManager($container, $discovery);

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
        $container = $this->mock(Container::class);
        $discovery = $this->mock(Discovery::class);

        $discovery->shouldReceive('getAssetType')
            ->with(Driver::class)
            ->andReturn(new ImmutableAssetType(Driver::class, [
                new Asset(Driver::class, '', '', 0, ['name' => 'fake']),
            ]))
            ->once();

        $container->shouldReceive('make')->with(Driver::class)->andReturn($driver = $this->mock(Driver::class))->once();

        $manager = new DriverManager($container, $discovery);

        $driver->shouldReceive('getConfig')->andReturn(['token'])->atLeast()->once();

        $channel = new Channel('test', 'fake', ['old' => $this->faker()->sha1]);

        $manager->get($channel);
    }
}
