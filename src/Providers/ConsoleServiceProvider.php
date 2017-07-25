<?php

declare(strict_types=1);

namespace FondBot\Providers;

use FondBot\Console;
use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
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
                Console\MakeIntent::class,
                Console\MakeInteraction::class,
                Console\ListDrivers::class,
                Console\InstallDriver::class,
                Console\ListChannels::class,
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
    }
}
