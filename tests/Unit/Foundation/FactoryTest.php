<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation;

use FondBot\Tests\TestCase;
use FondBot\Foundation\Kernel;
use FondBot\Foundation\Factory;

class FactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $this->container->share('resources_path', $resourcesPath = sys_get_temp_dir());

        $result = Factory::create($this->container);

        $this->assertInstanceOf(Kernel::class, $result);
        $this->assertSame($result, $result->resolve(Kernel::class));
    }
}
