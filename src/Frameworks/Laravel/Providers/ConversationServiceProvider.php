<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Providers;

use FondBot\Conversation\StoryManager;
use Illuminate\Support\ServiceProvider;
use FondBot\Conversation\Fallback\FallbackStory;

class ConversationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(StoryManager::class, function () {
            return tap(new StoryManager(), function (StoryManager $manager) {
                $this->registerStories($manager);
                $this->registerFallbackStory($manager);
            });
        });
    }

    private function registerStories(StoryManager $manager): void
    {
        /** @var array $stories */
        $stories = $this->app['config']->get('fondbot.stories', []);

        foreach ($stories as $story) {
            $manager->add(resolve($story));
        }
    }

    private function registerFallbackStory(StoryManager $manager): void
    {
        $manager->setFallbackStory(resolve(
            $this->app['config']->get('fondbot.fallback_story') ?? FallbackStory::class
        ));
    }
}
