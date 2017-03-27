<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Providers;

use FondBot\Channels\ChannelManager;
use Illuminate\Support\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ChannelManager::class, function () {
            return tap(new ChannelManager(), function (ChannelManager $manager) {
                $this->registerChannels($manager);
            });
        });
    }

    private function registerChannels(ChannelManager $manager): void
    {
        /** @var array $channels */
        $channels = $this->app['config']->get('fondbot.channels', []);

        foreach ($channels as $name => $parameters) {
            $manager->add($name, $parameters);
        }
    }
}
