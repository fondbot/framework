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
        $driver = $this->mock(Driver::class);

        $discovery->shouldReceive('getAssetType')
            ->with(Driver::class)
            ->andReturn(new ImmutableAssetType(Driver::class, [
                new Asset(get_class($driver), '', '', 0, ['name' => 'fake']),
            ]))
            ->atLeast()->once();

        $container->shouldReceive('make')->with(get_class($driver))->andReturn($driver)->once();

        $manager = new DriverManager($container, $discovery);

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
        $driver = $this->mock(Driver::class);

        $discovery->shouldReceive('getAssetType')
            ->with(Driver::class)
            ->andReturn(new ImmutableAssetType(Driver::class, [
                new Asset(get_class($driver), '', '', 0, ['name' => 'fake', 'parameters' => ['foo' => 'bar']]),
            ]))
            ->atLeast()->once();

        $container->shouldReceive('make')->with(get_class($driver))->andReturn($driver)->once();

        $manager = new DriverManager($container, $discovery);

        $channel = new Channel('test', 'fake', ['old' => $this->faker()->sha1]);

        $manager->get($channel);
    }
}
