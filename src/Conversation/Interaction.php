<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Conversation\Traits\InteractsWithContext;
use FondBot\Conversation\Traits\InteractsWithSession;

abstract class Interaction implements Conversable
{
    use InteractsWithSession,
        InteractsWithContext,
        SendsMessages,
        Transitions;

    /**
     * Run interaction.
     *
     * @param ReceivedMessage $message
     */
    abstract public function run(ReceivedMessage $message): void;

    /**
     * Process received message.
     *
     * @param ReceivedMessage $reply
     */
    abstract public function process(ReceivedMessage $reply): void;

    /**
     * Handle interaction.
     */
    public function handle(): void
    {
        $session = kernel()->getSession();

        if ($session->getInteraction() instanceof $this) {
            $this->process($session->getMessage());
        } else {
            kernel()->getSession()->setInteraction($this);
            $this->run($session->getMessage());
        }
    }
}
