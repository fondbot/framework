<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Bot;
use FondBot\Traits\Loggable;
use FondBot\Conversation\Traits\Transitions;
use FondBot\Conversation\Traits\Authorization;
use FondBot\Conversation\Traits\HasActivators;
use FondBot\Contracts\Conversation\Conversable;
use FondBot\Conversation\Traits\InteractsWithContext;
use FondBot\Contracts\Conversation\Story as StoryContract;

abstract class Story implements StoryContract, Conversable
{
    use Authorization, InteractsWithContext, HasActivators, Transitions, Loggable;

    /**
     * Handle story.
     *
     * @param Bot $bot
     */
    final public function handle(Bot $bot): void
    {
        $this->debug('handle');
        $this->bot = $bot;

        if (method_exists($this, 'before')) {
            $this->before();
        }

        // Run first interaction of the story
        $this->jump($this->firstInteraction());

        if (method_exists($this, 'after')) {
            $this->after();
        }
    }
}
