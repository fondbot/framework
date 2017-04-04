<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Bot;
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
     * @param Bot $bot
     */
    public function handle(Bot $bot): void
    {
        $this->bot = $bot;
        $context = $this->bot->getContext();

        if ($context->getInteraction() instanceof $this) {
            $this->process($context->getMessage());

            if (!$this->transitioned) {
                $this->bot->clearContext();
            }
        } else {
            $this->bot->getContext()->setInteraction($this);
            $this->run();
        }
    }
}
