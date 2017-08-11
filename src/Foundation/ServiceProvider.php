<?php

declare(strict_types=1);

namespace FondBot\Foundation;

use FondBot\Events\MessageReceived;
use FondBot\Channels\ChannelManager;
use Illuminate\Contracts\Cache\Store;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\FallbackIntent;
use FondBot\Conversation\SessionManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use FondBot\Foundation\Listeners\HandleConversation;
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
                // TODO
//                MakeIntent::class,
//                MakeInteraction::class,
//                ListDrivers::class,
//                InstallDriver::class,
//                ListChannels::class,
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
        $this->registerChannelManager();
        $this->registerSessionManager();
        $this->registerIntentManager();
        $this->registerKernel();
        $this->registerEvents();
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
            return new Kernel($this->app);
        });
    }

    private function registerEvents(): void
    {
        /** @var Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(MessageReceived::class, HandleConversation::class);
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
            ChannelManager::class,
            SessionManager::class,
            IntentManager::class,
            Kernel::class,
        ];
    }
}
