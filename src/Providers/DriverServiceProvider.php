<?php

declare(strict_types=1);

namespace FondBot\Providers;

use FondBot\Drivers\DriverManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository as Config;

class DriverServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(DriverManager::class, function () {
            /** @var Config $config */
            $config = $this->app[Config::class];

            $manager = new DriverManager;
            $manager->register($config->get('fondbot.drivers'));

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
        return [DriverManager::class];
    }
}
