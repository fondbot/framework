<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Toolbelt;

use FondBot\Tests\TestCase;
use FondBot\Toolbelt\Command;
use Symfony\Component\Console\Application;
use FondBot\Toolbelt\ToolbeltServiceProvider;

class ToolbeltServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(ToolbeltServiceProvider::class)->makePartial();
        $provider->shouldReceive('commands')->andReturn([
            $command = $this->mock(Command::class),
        ])->once();
        $command->shouldReceive('setApplication')->atLeast()->once();
        $command->shouldReceive('isEnabled')->atLeast()->once();

        $this->container->addServiceProvider($provider);

        /** @var Application $console */
        $console = $this->kernel->resolve('toolbelt');

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
