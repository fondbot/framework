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
        $this->container->add(Kernel::class, $this->kernel);
        Factory::create($this->container);

        /** @var Application $console */
        $console = $this->kernel->resolve('console');

        $this->assertInstanceOf(Application::class, $console);
        $this->assertTrue($console->has('make:intent'));
        $this->assertTrue($console->has('make:interaction'));
        $this->assertTrue($console->has('driver:list'));
        $this->assertTrue($console->has('driver:install'));
        $this->assertTrue($console->has('channel:list'));
        $this->assertTrue($console->has('log'));
        $this->assertTrue($console->has('queue:worker'));
    }
}
