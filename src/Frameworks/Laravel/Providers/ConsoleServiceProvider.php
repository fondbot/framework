<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../resources/config/fondbot.php' => config_path('fondbot.php'),
            ], 'fondbot');

            $this->commands([
                \FondBot\Frameworks\Laravel\Console\MakeStory::class,
                \FondBot\Frameworks\Laravel\Console\MakeInteraction::class,
                \FondBot\Frameworks\Laravel\Console\WebhookInstall::class,
                \FondBot\Frameworks\Laravel\Console\Install::class,
                \FondBot\Frameworks\Laravel\Console\Update::class,
            ]);
        }
    }
}
