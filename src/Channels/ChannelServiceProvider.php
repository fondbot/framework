<?php

declare(strict_types=1);

namespace FondBot\Channels;

use FondBot\Providers\ServiceProvider;

class ChannelServiceProvider extends ServiceProvider
{
    /**
     * Default drivers.
     *
     * @var array
     */
    private $drivers = [
        'Telegram'    => Telegram\TelegramDriver::class,
        'VkCommunity' => VkCommunity\VkCommunityDriver::class,
        'Facebook'    => Facebook\FacebookDriver::class,
    ];

    public function register()
    {
        $this->app->singleton(ChannelManager::class, function () {
            return new ChannelManager($this->drivers);
        });
    }
}
