<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit;

use FondBot\Tests\TestCase;
use FondBot\Foundation\Kernel;
use FondBot\Conversation\Session;

class HelpersTest extends TestCase
{
    public function testKernel(): void
    {
        $kernel = Kernel::createInstance($this->container);

        $this->assertSame($kernel, kernel());

        $this->container->instance('foo', 'bar');

        $this->assertSame('bar', kernel('foo'));
    }

    public function testSession(): void
    {
        $session = $this->mock(Session::class);
        $this->kernel->setSession($session);

        $this->assertSame($session, session());
    }
}
