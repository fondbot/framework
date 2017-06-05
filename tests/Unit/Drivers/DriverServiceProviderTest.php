<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Tests\TestCase;
use FondBot\Application\Assets;
use FondBot\Drivers\DriverManager;
use FondBot\Drivers\AbstractDriver;
use FondBot\Drivers\DriverServiceProvider;

class DriverServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $assetLoader = $this->mock(Assets::class);
        $fooDriver = $this->mock(AbstractDriver::class);
        $barDriver = $this->mock(AbstractDriver::class);

        $this->container->addServiceProvider(new DriverServiceProvider($assetLoader));

        $assetLoader->shouldReceive('all')->with('driver')->andReturn(['Foo', 'Bar']);

        $this->container->add('Foo', $fooDriver);
        $this->container->add('Bar', $barDriver);

        $fooDriver->shouldReceive('getShortName')->andReturn('foo')->once();
        $barDriver->shouldReceive('getShortName')->andReturn('bar')->once();

        /** @var DriverManager $manager */
        $manager = $this->container->get(DriverManager::class);

        $this->assertInstanceOf(DriverManager::class, $manager);
    }
}
