<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Drivers\User;
use FondBot\Tests\TestCase;
use FondBot\Application\Kernel;
use FondBot\Conversation\Session;
use FondBot\Conversation\Traits\InteractsWithSession;

class InteractsWithSessionTest extends TestCase
{
    public function test_remember(): void
    {
        $kernel = $this->mock(Kernel::class);
        $session = $this->mock(Session::class);

        $kernel->shouldReceive('getSession')->andReturn($session)->once();
        $session->shouldReceive('setValue')->with('foo', 'bar')->once();

        $class = new InteractsWithSessionTraitTestClass($kernel);
        $class->remember('foo', 'bar');
    }

    public function test_user(): void
    {
        $kernel = $this->mock(Kernel::class);
        $session = $this->mock(Session::class);
        $user = $this->mock(User::class);

        $kernel->shouldReceive('getSession')->andReturn($session)->once();
        $session->shouldReceive('getUser')->andReturn($user)->once();

        $class = new InteractsWithSessionTraitTestClass($kernel);
        $this->assertSame($user, $class->getUser());
    }
}

class InteractsWithSessionTraitTestClass
{
    use InteractsWithSession;

    protected $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function __call($name, $arguments)
    {
        return $this->$name(...$arguments);
    }
}
