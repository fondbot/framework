<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Bot;
use FondBot\Traits\Loggable;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Contracts\Conversation\Conversable;
use FondBot\Conversation\Traits\InteractsWithContext;
use FondBot\Contracts\Conversation\Story as StoryContract;

abstract class Story implements StoryContract, Conversable
{
    use InteractsWithContext, Transitions, Loggable;

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

    /**
     * Handle story.
     *
     * @param Bot $bot
     */
    public function handle(Bot $bot): void
    {
        $this->debug('handle');
        $this->bot = $bot;

        $this->before();

        // Run first interaction of the story
        $this->jump($this->firstInteraction());

        $this->after();
    }
}
