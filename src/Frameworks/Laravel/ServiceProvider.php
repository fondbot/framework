<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel;

use FondBot\Contracts\Cache\Cache as CacheContract;
use FondBot\Frameworks\Laravel\Cache\Cache;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->bindContracts();

        $this->app->register(Providers\ConversationServiceProvider::class);
        $this->app->register(Providers\ChannelServiceProvider::class);
        $this->app->register(Providers\DriverServiceProvider::class);
        $this->app->register(Providers\RouteServiceProvider::class);
        $this->app->register(Providers\ConsoleServiceProvider::class);
    }

    /**
     * Bind FondBot contracts with concrete Laravel classes.
     */
    private function bindContracts(): void
    {
        $this->app->bind(CacheContract::class, Cache::class);
    }
}
