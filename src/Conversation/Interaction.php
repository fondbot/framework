<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Contracts\Bot;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Contracts\Conversation\Conversable;
use FondBot\Conversation\Traits\InteractsWithContext;
use FondBot\Contracts\Conversation\Interaction as InteractionContract;

abstract class Interaction implements InteractionContract, Conversable
{
    use InteractsWithContext,
        SendsMessages,
        Transitions;

    /**
     * Handle interaction.
     *
     * @param Bot $bot
     */
    final public function handle(Bot $bot): void
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
