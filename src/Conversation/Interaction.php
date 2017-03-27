<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Bot;
use FondBot\Contracts\Conversation\Conversable;
use FondBot\Traits\Loggable;
use FondBot\Contracts\Channels\User;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Contracts\Conversation\Interaction as InteractionContract;

abstract class Interaction implements InteractionContract, Conversable
{
    use Transitions, Loggable;

    /**
     * Remember value in context.
     *
     * @param string $key
     * @param        $value
     */
    protected function remember(string $key, $value): void
    {
        $this->bot->getContext()->setValue($key, $value);
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->bot->getContext()->getUser();
    }

    /**
     * Do something before running Interaction.
     */
    protected function before(): void
    {
    }

    /**
     * Do something after running Interaction.
     */
    protected function after(): void
    {
    }

    /**
     * Handle interaction.
     *
     * @param Bot $bot
     */
    public function handle(Bot $bot): void
    {
        $this->bot = $bot;

        // Perform actions before running interaction
        $this->before();

        // Process reply if current interaction in context
        // Reply to participant if not
        if ($this->bot->getContext()->getInteraction() instanceof $this) {
            $this->debug('run.process');

            $this->process($this->bot->getContext()->getMessage());

            // If no transition run we need to clear context.
            if (!$this->transitioned) {
                $this->bot->clearContext();
            }

            $this->after();

            return;
        }

        // Set current interaction in context
        $this->bot->getContext()->setInteraction($this);

        // Send message to participant
        $this->bot->sendMessage($this->getUser(), $this->text(), $this->keyboard());

        // Perform actions after running interaction
        $this->after();
    }
}
