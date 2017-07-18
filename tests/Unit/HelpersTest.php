<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit;

use Monolog\Logger;
use FondBot\Tests\TestCase;
use FondBot\Foundation\Kernel;
use FondBot\Conversation\Session;

class HelpersTest extends TestCase
{
    public function testKernel(): void
    {
        $kernel = Kernel::createInstance($this->container);

        $this->assertSame($kernel, kernel());
    }

    public function testResolve(): void
    {
        $this->container->add('foo', 'bar');

        $this->assertSame('bar', resolve('foo'));
    }

    public function testSession(): void
    {
        $session = $this->mock(Session::class);
        $this->kernel->setSession($session);

        $this->assertSame($session, session());
    }

    public function testPath(): void
    {
        $this->container->add('base_path', 'foo');

        $this->assertSame('foo', path());
        $this->assertSame('foo/bar', path('bar'));
    }

    public function testResourcesPath(): void
    {
        $this->container->add('resources_path', 'foo');
        Kernel::createInstance($this->container);

        $this->assertSame('foo', resources());
        $this->assertSame('foo/bar', resources('bar'));
    }

    public function testLogger(): void
    {
        $logger = new Logger('foo');

        $this->container->add(Logger::class, $logger);

        $this->assertSame($logger, logger());
    }
}
