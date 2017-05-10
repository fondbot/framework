<?php

declare(strict_types=1);

namespace Tests\Unit\Drivers;

use FondBot\Tests\TestCase;
use FondBot\Drivers\DriverManager;
use FondBot\Drivers\DriverServiceProvider;

class DriverServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $this->container->addServiceProvider(new DriverServiceProvider());

        $this->assertInstanceOf(DriverManager::class, $this->container->get(DriverManager::class));
    }
}
