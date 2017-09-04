<?php

declare(strict_types=1);

namespace FondBot\Toolbelt;

use Illuminate\Support\ServiceProvider;

class ToolbeltServiceProvider extends ServiceProvider
{
    /**
     * Define toolbelt commands.
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
}
