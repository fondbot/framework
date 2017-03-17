<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Conversation\Traits\Transitions;
use FondBot\Traits\Loggable;

abstract class Story
{
    use Loggable, Transitions;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $enabled = true;

    /** @var Context */
    protected $context;

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

    public function run(Context $context): void
    {
        $this->context = $context;

        $this->before();
        $interaction = $context->getInteraction();

        // Story in already running
        // Process interaction from context
        if ($interaction !== null) {
            $interaction->setContext($context);
            $interaction->run();

            return;
        }

        // Run first interaction of story
        $this->jump($this->firstInteraction());
        $this->after();
    }
}
