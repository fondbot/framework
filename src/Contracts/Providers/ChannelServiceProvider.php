<?php

declare(strict_types=1);

namespace FondBot\Contracts\Providers;

use FondBot\Channels\Facebook;
use FondBot\Channels\Telegram;
use FondBot\Channels\VkCommunity;
use FondBot\Channels\ChannelManager;
use FondBot\Providers\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
{
    /**
     * Default drivers.
     *
     * @var array
     */
    private $drivers = [
        'facebook' => Facebook\FacebookDriver::class,
        'telegram' => Telegram\TelegramDriver::class,
        'vk-communities' => VkCommunity\VkCommunityDriver::class,
    ];

    public function register()
    {
        $this->app->singleton(ChannelManager::class, function () {
            return tap(new ChannelManager(), function (ChannelManager $manager) {
                $this->registerDrivers($manager);
            });
        });
    }

    private function registerDrivers(ChannelManager $manager): void
    {
        foreach ($this->drivers as $alias => $class) {
            $manager->add($alias, $class);
        }
    }
}
