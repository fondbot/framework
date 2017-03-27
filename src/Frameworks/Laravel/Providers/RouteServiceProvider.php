<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Providers;

use FondBot\Channels\ChannelManager;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $router->bind('channel', function ($value) {
            return $this->app[ChannelManager::class]->create($value);
        });

        if (!$this->app->routesAreCached()) {
            $router->group([
                'prefix' => 'fondbot',
                'namespace' => 'FondBot\Frameworks\Laravel\Http\Controllers',
                'middleware' => ['bindings'],
            ], function () use ($router) {
                $router->any('{channel}', [
                    'as' => 'fondbot.webhook',
                    'uses' => 'WebhookController@handle',
                ]);
            });
        }
    }
}