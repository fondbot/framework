<?php

declare(strict_types=1);

namespace FondBot\Contracts\Providers;

use FondBot\Conversation\StoryManager;
use Illuminate\Support\ServiceProvider;
use FondBot\Conversation\Fallback\FallbackStory;

class ConversationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(StoryManager::class, function () {
            return new StoryManager(
                config('fondbot.stories', []),
                config('fondbot.fallback_story', FallbackStory::class)
            );
        });
    }
}
