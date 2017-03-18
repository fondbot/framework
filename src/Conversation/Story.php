<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Traits\Loggable;
use FondBot\Conversation\Traits\Transitions;

abstract class Story
{
    use Transitions, Loggable;

    /** @var string */
    protected $name;

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

    public function run(): void
    {
        $this->debug('run', [
            'name' => $this->name,
            'firstInteraction' => $this->firstInteraction(),
            'context' => $this->context,
        ]);

        $this->before();
        $interaction = $this->context->getInteraction();

        // Story in already running
        // Process interaction from context
        if ($interaction !== null) {
            $interaction->setContext($this->context);
            $interaction->run();

            return;
        }

        // Run first interaction of story
        $this->jump($this->firstInteraction());
        $this->after();
    }
}
