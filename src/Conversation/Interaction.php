<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Conversable;
use FondBot\Events\MessageReceived;
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
        $session = session();

        if ($session->getInteraction() instanceof $this) {
            $this->process($message);
        } else {
            session()->setInteraction($this);
            $this->run($message);
        }
    }
}
