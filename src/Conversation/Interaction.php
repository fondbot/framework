<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Events\MessageReceived;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Contracts\Conversation\Conversable;
use FondBot\Conversation\Traits\InteractsWithContext;

abstract class Interaction implements Conversable
{
    use InteractsWithContext,
        SendsMessages,
        Transitions;

    /**
     * Run interaction.
     *
     * @param MessageReceived $message
     */
    abstract public function run(MessageReceived $message): void;

    /**
     * Process received message.
     *
     * @param MessageReceived $reply
     */
    abstract public function process(MessageReceived $reply): void;

    /**
     * Handle interaction.
     *
     * @param MessageReceived $message
     */
    public function handle(MessageReceived $message): void
    {
        $context = context();

        if ($context->getInteraction() instanceof $this) {
            $this->process($message);
        } else {
            $context->setInteraction($this);
            $this->run($message);
        }
    }
}
