<?php

declare(strict_types=1);

namespace FondBot\Conversation\Providers;

use FondBot\Application\Config;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\FallbackIntent;
use League\Container\ServiceProvider\AbstractServiceProvider;

class IntentServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        IntentManager::class,
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
        /** @var Config $config */
        $config = $this->getContainer()->get(Config::class);

        /** @var array $intents */
        $intents = $config->get('intents', []);

        /** @var string $fallbackIntent */
        $fallbackIntent = $config->get('fallback_intent', FallbackIntent::class);

        $manager = new IntentManager();

        foreach ($intents as $intent) {
            $manager->add($this->getContainer()->get($intent));
        }

        $manager->setFallbackIntent($this->getContainer()->get($fallbackIntent));

        $this->getContainer()->add(IntentManager::class, $manager);
    }
}
