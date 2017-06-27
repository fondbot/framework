<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit;

use Monolog\Logger;
use FondBot\Tests\TestCase;
use FondBot\Foundation\Kernel;
use FondBot\Conversation\Session;

class HelpersTest extends TestCase
{
    public function test_env(): void
    {
        $_ENV['foo'] = 'bar';
        $this->assertSame('bar', env('foo'));

        $_ENV['foo'] = 'true';
        $this->assertTrue(env('foo'));

        $_ENV['foo'] = 'false';
        $this->assertFalse(env('foo'));

        $_ENV['foo'] = 'null';
        $this->assertNull(env('foo'));

        $this->assertNull(env('x'));
        $this->assertSame('y', env('x', 'y'));
    }

    public function test_kernel(): void
    {
        $kernel = Kernel::createInstance($this->container);

        $this->assertSame($kernel, kernel());
    }

    public function test_resolve(): void
    {
        $this->container->add('foo', 'bar');
        Kernel::createInstance($this->container);

        $this->assertSame('bar', resolve('foo'));
    }

    public function test_session(): void
    {
        $kernel = Kernel::createInstance($this->container);
        $session = $this->mock(Session::class);
        $kernel->setSession($session);

        $this->assertSame($session, session());
    }

    public function test_path(): void
    {
        $this->container->add('base_path', 'foo');
        Kernel::createInstance($this->container);

        $this->assertSame('foo', path());
        $this->assertSame('foo/bar', path('bar'));
    }

    public function test_resources_path(): void
    {
        $this->container->add('resources_path', 'foo');
        Kernel::createInstance($this->container);

        $this->assertSame('foo', resources());
        $this->assertSame('foo/bar', resources('bar'));
    }

    public function test_logger(): void
    {
        $logger = new Logger('foo');

        $this->container->add(Logger::class, $logger);
        Kernel::createInstance($this->container);

        $this->assertSame($logger, logger());
    }
}
