<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Tests\TestCase;
use FondBot\Application\Kernel;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\Traits\Transitions;

class TransitionsTest extends TestCase
{
    public function test_jump()
    {
        $kernel = $this->mock(Kernel::class);

        $kernel->shouldReceive('resolve')->with('foo')->andReturn($interaction = $this->mock(Interaction::class))->once();
        $kernel->shouldReceive('converse')->with($interaction)->once();

        $class = new TransitionsTraitTestClass($kernel);
        $class->jump('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid interaction `foo`
     */
    public function test_jump_invalid_interaction()
    {
        $kernel = $this->mock(Kernel::class);

        $kernel->shouldReceive('resolve')->with('foo')->andReturn($this->mock(Intent::class))->once();
        $kernel->shouldReceive('converse')->never();

        $class = new TransitionsTraitTestClass($kernel);
        $class->jump('foo');
    }
}

class TransitionsTraitTestClass
{
    use Transitions;

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
