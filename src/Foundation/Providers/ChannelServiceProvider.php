<?php

declare(strict_types=1);

namespace FondBot\Foundation\Providers;

use FondBot\Framework\Application;
use FondBot\Channels\ChannelManager;
use FondBot\Contracts\Channels\Manager;
use Illuminate\Support\ServiceProvider;

/**
 * @property Application $app
 */
class ChannelServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        $this->registerManager();
    }

    /**
     * Boot application services.
     */
    public function boot(): void
    {
        /** @var ChannelManager $manager */
        $manager = $this->app[Manager::class];

        $manager->register(
            collect($this->channels())
                ->mapWithKeys(function (array $parameters, string $name) {
                    return [$name => $parameters];
                })
                ->toArray()
        );
    }

    /**
     * Define bot channels.
     *
     * @return array
     */
    protected function channels(): array
    {
        return [];
    }

    private function registerManager(): void
    {
        $this->app->singleton(Manager::class, function () {
            return new ChannelManager($this->app);
        });

        $this->app->alias(Manager::class, ChannelManager::class);
    }
}
