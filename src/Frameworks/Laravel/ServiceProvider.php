<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel;

use FondBot\Bot;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->alias(Bot::class, 'fondbot');

        $this->app->register(Providers\ConversationServiceProvider::class);
        $this->app->register(Providers\ChannelServiceProvider::class);
        $this->app->register(Providers\DriverServiceProvider::class);
        $this->app->register(Providers\RouteServiceProvider::class);
        $this->app->register(Providers\ConsoleServiceProvider::class);
    }
}
