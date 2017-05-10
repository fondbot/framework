<?php

declare(strict_types=1);

namespace FondBot\Application;

use FondBot\Conversation\FallbackIntent;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ConfigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Config::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
        // Load configuration
        $this->getContainer()->share(Config::class, function () {
            $config = new Config();
            $config->set('channels', []);
            $config->set('intents', []);
            $config->set('fallback_intent', FallbackIntent::class);

            return $config;
        });
    }
}
