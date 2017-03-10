<?php
declare(strict_types=1);

namespace FondBot\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../resources/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../resources/routes/channels.php');

        $this->publishes([
            __DIR__ . '/../../resources/config/fondbot.php' => config_path('fondbot.php'),
        ], 'fondbot');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \FondBot\Console\CreateStory::class,
                \FondBot\Console\Install::class,
            ]);
        }
    }

}