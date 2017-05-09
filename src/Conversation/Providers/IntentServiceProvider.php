<?php

declare(strict_types=1);

namespace FondBot\Conversation\Providers;

use FondBot\Conversation\IntentManager;
use FondBot\Conversation\FallbackIntent;
use League\Container\ServiceProvider\AbstractServiceProvider;

class IntentServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        IntentManager::class,
    ];

    private $intents;
    private $fallbackIntent;

    public function __construct(array $intents, string $fallbackIntent = FallbackIntent::class)
    {
        $this->intents = $intents;
        $this->fallbackIntent = $fallbackIntent;
    }

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register(): void
    {
        $manager = new IntentManager();

        foreach ($this->intents as $intent) {
            $manager->add($this->getContainer()->get($intent));
        }

        $manager->setFallbackIntent($this->getContainer()->get($this->fallbackIntent));

        $this->getContainer()->add(IntentManager::class, $manager);
    }
}
