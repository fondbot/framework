<?php

declare(strict_types=1);

namespace FondBot\Providers;

use FondBot\Channels\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository as Config;

class ChannelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ChannelManager::class, function () {
            /** @var Config $config */
            $config = $this->app[Config::class];

            /** @var array $channels */
            $channels = collect($config->get('fondbot.channels', []))
                ->mapWithKeys(function (array $parameters, string $name) {
                    return [$name => $parameters];
                });

            return new ChannelManager($channels);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [ChannelManager::class];
    }
}
