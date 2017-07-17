<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Tests\TestCase;
use FondBot\Foundation\Kernel;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Session;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\ConversationManager;
use FondBot\Conversation\Activators\Activator;

class TransitionsTest extends TestCase
{
    public function testJump(): void
    {
        $conversationManager = $this->mock(ConversationManager::class);
        $this->container->add('foo', $interaction = $this->mock(Interaction::class));

        $conversationManager->shouldReceive('transition')->with($interaction)->once();

        $class = new TransitionsTraitTestClass($this->kernel);
        $class->jump('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid interaction `foo`
     */
    public function testJumpInvalidInteraction(): void
    {
        $conversationManager = $this->mock(ConversationManager::class);
        $this->container->add('foo', $this->mock(Intent::class));

        $conversationManager->shouldReceive('converse')->never();

        $class = new TransitionsTraitTestClass($this->kernel);
        $class->jump('foo');
    }

    public function testRestartIntent(): void
    {
        $conversationManager = $this->mock(ConversationManager::class);
        $session = $this->mock(Session::class);
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

            public function run(ReceivedMessage $message): void
            {
                $this->restart();
            }
        };

        $this->kernel->setSession($session);

        $session->shouldReceive('getMessage')->andReturn($this->mock(ReceivedMessage::class));
        $conversationManager->shouldReceive('restart')->with($intent)->once();

        $intent->handle($this->kernel);
    }

    public function testRestartInteraction(): void
    {
        $conversationManager = $this->mock(ConversationManager::class);
        $session = $this->mock(Session::class);
        $this->kernel->setSession($session);

        $interaction = new class extends Interaction {
            /**
             * Run interaction.
             *
             * @param ReceivedMessage $message
             */
            public function run(ReceivedMessage $message): void
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

        $session->shouldReceive('getInteraction')->andReturn($interaction)->once();
        $session->shouldReceive('getMessage')->andReturn($this->mock(ReceivedMessage::class))->once();

        $conversationManager->shouldReceive('restart')->with($interaction)->once();

        $interaction->handle($this->kernel);
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
