<?php

declare(strict_types=1);

namespace FondBot\Providers;

use FondBot\Foundation\Kernel;
use Illuminate\Contracts\Cache\Store;
use FondBot\Conversation\IntentManager;
use Illuminate\Support\ServiceProvider;
use FondBot\Conversation\FallbackIntent;
use FondBot\Conversation\SessionManager;
use FondBot\Conversation\ConversationManager;
use Illuminate\Contracts\Config\Repository as Config;

class ConversationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ConversationManager::class, function () {
            return new ConversationManager($this->app->make(Kernel::class));
        });

        $this->app->singleton(SessionManager::class, function () {
            return new SessionManager(
                $this->app,
                $this->app->make(Store::class)
            );
        });

        $this->app->singleton(IntentManager::class, function () {
            /** @var Config $config */
            $config = $this->app[Config::class];

            $intents = $config->get('fondbot.intents', []);
            $fallbackIntent = $config->get('fondbot.fallback_intent', FallbackIntent::class);

            $manager = new IntentManager;
            $manager->register($intents, $fallbackIntent);

            return $manager;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            ConversationManager::class,
            SessionManager::class,
            IntentManager::class,
        ];
    }
}
