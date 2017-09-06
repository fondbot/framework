<?php

declare(strict_types=1);

namespace FondBot\Channels;

use Illuminate\Support\ServiceProvider;

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
            return tap(new ChannelManager($this->app), function (ChannelManager $manager) {
                /** @var array $channels */
                $channels = collect(config('fondbot.channels'))
                    ->mapWithKeys(function (array $parameters, string $name) {
                        return [$name => $parameters];
                    })
                    ->toArray();

                $manager->register($channels);
            });
        });
    }
}
