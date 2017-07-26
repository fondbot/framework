<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Console\MakeIntent;
use FondBot\Console\ListDrivers;
use FondBot\Console\ListChannels;
use FondBot\Console\InstallDriver;
use FondBot\Drivers\DriverManager;
use FondBot\Channels\ChannelManager;
use FondBot\Console\MakeInteraction;
use Illuminate\Contracts\Cache\Store;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\FallbackIntent;
use FondBot\Conversation\SessionManager;
use Illuminate\Contracts\Config\Repository;
use FondBot\Conversation\ConversationManager;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeIntent::class,
                MakeInteraction::class,
                ListDrivers::class,
                InstallDriver::class,
                ListChannels::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConfiguration();
        $this->registerRoutes();
        $this->registerDriverManager();
        $this->registerChannelManager();
        $this->registerConversationManager();
        $this->registerSessionManager();
        $this->registerIntentManager();
        $this->registerKernel();
    }

    private function registerConfiguration()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/fondbot.php', 'fondbot');
    }

    private function registerRoutes(): void
    {
        if (get_class($this->app) === 'Laravel\Lumen\Application') {
            $router = $this->app;

            require __DIR__.'/../../resources/routes.php';
        } else {
            $this->loadRoutesFrom(__DIR__.'/../../resources/routes.php');
        }
    }

    private function registerDriverManager(): void
    {
        $this->app->singleton(DriverManager::class, function () {
            $manager = new DriverManager;
            $manager->register(array_get($this->config(), 'drivers', []));

            return $manager;
        });
    }

    private function registerChannelManager(): void
    {
        $this->app->singleton(ChannelManager::class, function () {
            /** @var array $channels */
            $channels = collect(array_get($this->config(), 'channels', []))
                ->mapWithKeys(function (array $parameters, string $name) {
                    return [$name => $parameters];
                })
                ->toArray();

            $manager = new ChannelManager;
            $manager->register($channels);

            return $manager;
        });
    }

    private function registerConversationManager(): void
    {
        $this->app->singleton(ConversationManager::class, function () {
            return new ConversationManager($this->app->make(Kernel::class));
        });
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

    private function registerKernel(): void
    {
        $this->app->singleton(Kernel::class, function () {
            return Kernel::createInstance($this->app);
        });
    }

    private function config(): array
    {
        /** @var Repository $config */
        $config = $this->app[Repository::class];

        return $config->get('fondbot');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            DriverManager::class,
            ChannelManager::class,
            ConversationManager::class,
            SessionManager::class,
            IntentManager::class,
            Kernel::class,
        ];
    }
}
