<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Application\Kernel;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Conversation\Traits\InteractsWithContext;

abstract class Interaction implements Conversable
{
    use InteractsWithContext,
        SendsMessages,
        Transitions;

    /**
     * Run interaction.
     */
    abstract public function run(): void;

    /**
     * Process received message.
     *
     * @param ReceivedMessage $reply
     */
    abstract public function process(ReceivedMessage $reply): void;

    /**
     * Handle interaction.
     *
     * @param Kernel $kernel
     */
    public function handle(Kernel $kernel): void
    {
        $this->kernel = $kernel;
        $context = $this->kernel->getContext();

        if ($context->getInteraction() instanceof $this) {
            $this->process($context->getMessage());

            if (!$this->transitioned) {
                $this->kernel->clearContext();
            }
        } else {
            $this->kernel->getContext()->setInteraction($this);
            $this->run();
        }
    }
}
