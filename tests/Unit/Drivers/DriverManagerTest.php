<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Drivers\DriverManager;
use Psr\Http\Message\RequestInterface;

class DriverManagerTest extends TestCase
{
    public function test_get(): void
    {
        $driver = $this->mock(Driver::class);
        $channel = $this->mock(Channel::class);
        $request = $this->mock(RequestInterface::class);
        $manager = new DriverManager;

        $driver->shouldReceive('getShortName')->andReturn('foo')->once();

        $manager->add($driver);

        $channel->shouldReceive('getDriver')->andReturn('foo')->once();
        $channel->shouldReceive('getParameters')->andReturn(['bar' => 'foo'])->once();
        $driver->shouldReceive('initialize')->with(['bar' => 'foo'], $request)->once();

        $this->assertSame($driver, $manager->get($channel, $request));
    }

    /**
     * @expectedException \FondBot\Drivers\Exceptions\DriverNotFound
     * @expectedExceptionMessage Driver `foo` not found.
     */
    public function test_get_driver_does_not_exist(): void
    {
        $channel = $this->mock(Channel::class);
        $request = $this->mock(RequestInterface::class);
        $manager = new DriverManager;

        $channel->shouldReceive('getDriver')->andReturn('foo')->atLeast()->once();

        $manager->get($channel, $request);
    }
}
