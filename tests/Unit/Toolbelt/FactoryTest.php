<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt;

use FondBot\Tests\TestCase;
use FondBot\Toolbelt\Factory;
use FondBot\Application\Kernel;
use Symfony\Component\Console\Application;

class FactoryTest extends TestCase
{
    public function test(): void
    {
        $kernel = Factory::create($this->container);

        $this->assertInstanceOf(Kernel::class, $kernel);
        $this->assertInstanceOf(Application::class, $kernel->resolve('console'));
    }
}
