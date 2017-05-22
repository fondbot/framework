<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Tests\TestCase;
use FondBot\Application\Kernel;
use FondBot\Application\Factory;

class FactoryTest extends TestCase
{
    public function test_create(): void
    {
        $this->container->share('resources_path', $resourcesPath = sys_get_temp_dir());

        $result = Factory::create($this->container);

        $this->assertInstanceOf(Kernel::class, $result);
        $this->assertSame($result, $result->resolve(Kernel::class));
    }
}
