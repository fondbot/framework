<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Http\Request;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Drivers\DriverManager;

class DriverManagerTest extends TestCase
{
    public function testGet(): void
    {
        $driver = $this->mock(Driver::class);
        $channel = $this->mock(Channel::class);
        $request = $this->mock(Request::class);
        $manager = new DriverManager($this->container);

        $manager->add($driver, 'foo', ['bar']);

        $channel->shouldReceive('getDriver')->andReturn('foo')->atLeast()->once();
        $channel->shouldReceive('getParameters')->andReturn(['bar' => 'foo'])->once();
        $channel->shouldReceive('getParameter')->with('bar')->andReturn('foo')->once();
        $driver->shouldReceive('fill')->with(['bar' => 'foo'], $request)->once();

        $this->assertSame($driver, $manager->get($channel, $request));
    }

    /**
     * @expectedException \FondBot\Drivers\Exceptions\DriverNotFound
     * @expectedExceptionMessage Driver `foo` not found.
     */
    public function testGetDriverDoesNotExist(): void
    {
        $channel = $this->mock(Channel::class);
        $request = $this->mock(Request::class);
        $manager = new DriverManager($this->container);

        $channel->shouldReceive('getDriver')->andReturn('foo')->atLeast()->once();

        $manager->get($channel, $request);
    }

    /**
     * @expectedException \FondBot\Drivers\Exceptions\InvalidConfiguration
     * @expectedExceptionMessage Invalid `foo` channel configuration.
     */
    public function testGetInvalidConfiguration(): void
    {
        $driver = $this->mock(Driver::class);
        $channel = $this->mock(Channel::class);
        $request = $this->mock(Request::class);
        $manager = new DriverManager($this->container);

        $manager->add($driver, 'foo', ['bar']);

        $channel->shouldReceive('getName')->andReturn('foo')->once();
        $channel->shouldReceive('getDriver')->andReturn('foo')->atLeast()->once();
        $channel->shouldReceive('getParameter')->with('bar')->andReturnNull()->once();

        $manager->get($channel, $request);
    }
}
