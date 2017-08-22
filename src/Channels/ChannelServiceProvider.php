<?php

declare(strict_types=1);

namespace FondBot\Channels;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository;

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
            /** @var array $channels */
            $channels = collect($this->config())
                ->mapWithKeys(function (array $parameters, string $name) {
                    return [$name => $parameters];
                })
                ->toArray();

            $manager = new ChannelManager($this->app);
            $manager->register($channels);

            return $manager;
        });
    }

    private function config(): array
    {
        /** @var Repository $config */
        $config = $this->app[Repository::class];

        return $config->get('fondbot.channels');
    }
}
