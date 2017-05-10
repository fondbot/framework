<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use FondBot\Application\Config;
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
        $this->getContainer()->share(IntentManager::class, function () {
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

            return $manager;
        });
    }
}
