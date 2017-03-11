<?php
declare(strict_types=1);

namespace FondBot\Providers;

use FondBot\Database\Entities\Channel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Route;

class ServiceProvider extends BaseServiceProvider
{

    public function register()
    {
        $this->console();
    }

    public function boot()
    {
        $this->routes();
    }

    private function routes(): void
    {
        Route::model('channel', Channel::class);

        if (!$this->app->routesAreCached()) {
            Route::group([
                'prefix' => 'fondbot',
                'namespace' => 'FondBot\Http\Controllers',
                'middleware' => ['bindings'],
            ], function () {
                require __DIR__ . '/../../resources/routes/channels.php';
            });
        }
    }

    private function console(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../resources/migrations');
            $this->publishes([
                __DIR__ . '/../../resources/config/fondbot.php' => config_path('fondbot.php'),
            ], 'fondbot');

            $this->commands([
                \FondBot\Console\CreateChannel::class,
                \FondBot\Console\DeleteChannel::class,
                \FondBot\Console\ListChannels::class,
                \FondBot\Console\CreateStory::class,
                \FondBot\Console\CreateInteraction::class,
                \FondBot\Console\WebhookInstall::class,
                \FondBot\Console\Install::class,
            ]);
        }
    }

}