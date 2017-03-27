<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Bot;
use FondBot\Traits\Loggable;
use FondBot\Conversation\Traits\Transitions;

abstract class Story
{
    use Transitions, Loggable;

    /**
     * Story activations.
     *
     * @return array
     */
    abstract public function activations(): array;

    /**
     * Interaction class name which will be run when activation is triggered.
     *
     * @return string
     */
    abstract public function firstInteraction(): string;

    /**
     * Do something before running Story.
     */
    protected function before(): void
    {
    }

    /**
     * Do something after running Story.
     */
    protected function after(): void
    {
    }

    public function handle(Bot $bot): void
    {
        $this->bot = $bot;
        $this->run();
    }

    private function run(): void
    {
        $this->debug('run', [
            'firstInteraction' => $this->firstInteraction(),
        ]);

        $this->before();

        // Story in already running
        // Process interaction from context
        if ($interaction = $this->bot->getContext()->getInteraction()) {
            $interaction->handle($this->bot);

            return;
        }

        // Run first interaction of story
        $this->jump($this->firstInteraction());
        $this->after();
    }
}
