<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Cache\Repository as Cache;

class ConversationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerSessionManager();
        $this->registerIntentManager();
    }

    private function registerSessionManager(): void
    {
        $this->app->singleton(SessionManager::class, function () {
            return new SessionManager($this->app, resolve(Cache::class));
        });
    }

    private function registerIntentManager(): void
    {
        $this->app->singleton(IntentManager::class, function () {
            $intents = config('fondbot.conversation.intents');
            $fallbackIntent = config('fondbot.conversation.fallback_intent', FallbackIntent::class);

            $manager = new IntentManager;
            $manager->register($intents, $fallbackIntent);

            return $manager;
        });
    }
}
