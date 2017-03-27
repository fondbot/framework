<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel;

use FondBot\Bot;
use FondBot\Channels\ChannelManager;
use Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->alias(Bot::class, 'fondbot');

        $this->app->register(Providers\ConversationServiceProvider::class);
        $this->app->register(Providers\ChannelServiceProvider::class);
        $this->app->register(Providers\DriverServiceProvider::class);
        $this->console();
    }

    public function boot()
    {
        $this->routes();
    }

    /**
     * Register routes.
     */
    private function routes(): void
    {
        Route::bind('channel', function ($value) {
            return $this->app[ChannelManager::class]->create($value);
        });

        if (!$this->app->routesAreCached()) {
            Route::group([
                'prefix' => 'fondbot',
                'namespace' => 'FondBot\Frameworks\Laravel\Http\Controllers',
                'middleware' => ['bindings'],
            ], function () {
                Route::any('{channel}', [
                    'as' => 'fondbot.webhook',
                    'uses' => 'WebhookController@handle',
                ]);

            });
        }
    }

    /**
     * Register console commands.
     */
    private function console(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../../resources/migrations');
            $this->publishes([
                __DIR__.'/../../resources/config/fondbot.php' => config_path('fondbot.php'),
            ], 'fondbot');

            $this->commands([
                Console\CreateStory::class,
                Console\CreateInteraction::class,
                Console\WebhookInstall::class,
                Console\Install::class,
                Console\Update::class,
            ]);
        }
    }
}
