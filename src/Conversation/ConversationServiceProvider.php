<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use Illuminate\Support\ServiceProvider;

class ConversationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(StoryManager::class, function () {
            return new StoryManager(config('fondbot.stories'));
        });
    }
}
