<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Drivers\DriverManager;
use TheCodingMachine\Discovery\Asset;
use TheCodingMachine\Discovery\Discovery;
use Psr\Http\Message\ServerRequestInterface;
use TheCodingMachine\Discovery\ImmutableAssetType;

class DriverManagerTest extends TestCase
{
    public function test_get(): void
    {
        $request = $this->mock(ServerRequestInterface::class);
        $discovery = $this->mock(Discovery::class);
        $driver = $this->mock(Driver::class);
        $parameters = ['token' => $this->faker()->sha1];

        $this->container->add(get_class($driver), $driver);

        $discovery->shouldReceive('getAssetType')
            ->with(Driver::class)
            ->andReturn(new ImmutableAssetType(Driver::class, [
                new Asset(get_class($driver), '', '', 0, ['name' => 'fake']),
            ]))
            ->atLeast()->once();

        $manager = new DriverManager($this->container, $discovery);

        $driver->shouldReceive('fill')->with($parameters, $request)->once();

        $channel = new Channel('test', 'fake', $parameters);

        $driver = $manager->get($channel, $request);

        $this->assertInstanceOf(Driver::class, $driver);
    }

    /**
     * @expectedException \FondBot\Drivers\Exceptions\DriverNotFound
     * @expectedExceptionMessage Driver `fake` not found.
     */
    public function test_get_driver_does_not_exist(): void
    {
        $request = $this->mock(ServerRequestInterface::class);
        $discovery = $this->mock(Discovery::class);
        $this->container->add('Foo', null);

        $discovery->shouldReceive('getAssetType')
            ->with(Driver::class)
            ->andReturn(new ImmutableAssetType(Driver::class, [
                new Asset('Foo', '', '', 0, ['name' => 'fake']),
            ]))
            ->atLeast()->once();

        $manager = new DriverManager($this->container, $discovery);

        $channel = new Channel('test', 'fake', ['token' => $this->faker()->sha1]);

        $manager->get($channel, $request);
    }

    /**
     * @expectedException \FondBot\Drivers\Exceptions\InvalidConfiguration
     * @expectedExceptionMessage Invalid `test` channel configuration.
     */
    public function test_get_invalid_configuration(): void
    {
        $request = $this->mock(ServerRequestInterface::class);
        $discovery = $this->mock(Discovery::class);
        $driver = $this->mock(Driver::class);
        $this->container->add(get_class($driver), $driver);

        $discovery->shouldReceive('getAssetType')
            ->with(Driver::class)
            ->andReturn(new ImmutableAssetType(Driver::class, [
                new Asset(get_class($driver), '', '', 0, ['name' => 'fake', 'parameters' => ['foo' => 'bar']]),
            ]))
            ->atLeast()->once();

        $manager = new DriverManager($this->container, $discovery);

        $channel = new Channel('test', 'fake', ['old' => $this->faker()->sha1]);

        $manager->get($channel, $request);
    }
}
