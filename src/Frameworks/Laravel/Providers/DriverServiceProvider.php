<?php

declare(strict_types=1);

namespace FondBot\Frameworks\Laravel\Providers;

use FondBot\Channels\Facebook;
use FondBot\Channels\Telegram;
use FondBot\Channels\VkCommunity;
use FondBot\Channels\DriverManager;
use FondBot\Frameworks\Laravel\ServiceProvider;

class DriverServiceProvider extends ServiceProvider
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
        $this->app->singleton(DriverManager::class, function () {
            return tap(new DriverManager(), function (DriverManager $manager) {
                $this->registerDrivers($manager);
            });
        });
    }

    private function registerDrivers(DriverManager $manager): void
    {
        foreach ($this->drivers as $alias => $class) {
            $manager->add($alias, resolve($class));
        }
    }
}
