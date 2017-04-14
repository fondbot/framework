<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Drivers\User;
use FondBot\Tests\TestCase;
use FondBot\Application\Kernel;
use FondBot\Conversation\Context;
use FondBot\Conversation\Traits\InteractsWithContext;

class InteractsWithContextTest extends TestCase
{
    public function test_remember()
    {
        $kernel = $this->mock(Kernel::class);
        $context = $this->mock(Context::class);

        $kernel->shouldReceive('getContext')->andReturn($context)->once();
        $context->shouldReceive('setValue')->with('foo', 'bar')->once();

        $class = new InteractsWithContextTraitTestClass($kernel);
        $class->remember('foo', 'bar');
    }

    public function test_user()
    {
        $kernel = $this->mock(Kernel::class);
        $context = $this->mock(Context::class);
        $user = $this->mock(User::class);

        $kernel->shouldReceive('getContext')->andReturn($context)->once();
        $context->shouldReceive('getUser')->andReturn($user)->once();

        $class = new InteractsWithContextTraitTestClass($kernel);
        $this->assertSame($user, $class->getUser());
    }
}

class InteractsWithContextTraitTestClass
{
    use InteractsWithContext;

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
