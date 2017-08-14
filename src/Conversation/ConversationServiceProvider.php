<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository;

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
            return new SessionManager(
                $this->app,
                $this->app->make(Store::class)
            );
        });
    }

    private function registerIntentManager(): void
    {
        $this->app->singleton(IntentManager::class, function () {
            $intents = array_get($this->config(), 'intents', []);
            $fallbackIntent = array_get($this->config(), 'fallback_intent', FallbackIntent::class);

            $manager = new IntentManager;
            $manager->register($intents, $fallbackIntent);

            return $manager;
        });
    }

    private function config(): array
    {
        /** @var Repository $config */
        $config = $this->app[Repository::class];

        return $config->get('fondbot.conversation');
    }
}
