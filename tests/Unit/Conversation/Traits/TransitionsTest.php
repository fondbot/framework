<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Tests\TestCase;
use FondBot\Application\Kernel;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Context;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Activators\Activator;

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

    public function test_restart_intent()
    {
        $kernel = $this->mock(Kernel::class);
        $intent = new class extends Intent {
            /**
             * Intent activators.
             *
             * @return Activator[]
             */
            public function activators(): array
            {
                return [];
            }

            /**
             * Run intent.
             */
            public function run(): void
            {
                $this->restart();
            }
        };

        $kernel->shouldReceive('clearContext')->once();
        $kernel->shouldReceive('converse')->with($intent)->once();

        $intent->handle($kernel);
    }

    public function test_restart_interaction()
    {
        $kernel = $this->mock(Kernel::class);
        $context = $this->mock(Context::class);
        $interaction = new class extends Interaction {
            /**
             * Run interaction.
             */
            public function run(): void
            {
            }

            /**
             * Process received message.
             *
             * @param ReceivedMessage $reply
             */
            public function process(ReceivedMessage $reply): void
            {
                $this->restart();
            }
        };

        $context->shouldReceive('getInteraction')->andReturn($interaction)->once();
        $context->shouldReceive('getMessage')->andReturn($this->mock(ReceivedMessage::class))->once();
        $kernel->shouldReceive('getContext')->andReturn($context)->atLeast()->once();
        $context->shouldReceive('setInteraction')->with(null)->once();
        $context->shouldReceive('setValues')->with([])->once();
        $kernel->shouldReceive('setContext')->once();
        $kernel->shouldReceive('converse')->with($interaction)->once();

        $interaction->handle($kernel);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Only conversable instances can be restarted.
     */
    public function test_restart_not_converable()
    {
        $class = new TransitionsTraitTestClass($this->mock(Kernel::class));
        $class->restart();
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
