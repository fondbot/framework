<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\DriverManager;
use TheCodingMachine\Discovery\Asset;
use TheCodingMachine\Discovery\Discovery;
use FondBot\Drivers\DriverServiceProvider;
use TheCodingMachine\Discovery\ImmutableAssetType;

class DriverServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $driver = $this->mock(Driver::class);
        $discovery = $this->mock(Discovery::class);

        $this->container->add(get_class($driver), $driver);

        $discovery->shouldReceive('getAssetType')
            ->with(Driver::class)
            ->andReturn(new ImmutableAssetType(Driver::class, [
                new Asset(get_class($driver), '', '', 0, ['name' => 'foo']),
            ]))
            ->atLeast()->once();

        $this->container->addServiceProvider(new DriverServiceProvider($discovery));

        /** @var DriverManager $manager */
        $manager = $this->container->get(DriverManager::class);

        $this->assertInstanceOf(DriverManager::class, $manager);
        $this->assertSame(['foo' => $driver], $manager->all());
    }
}
