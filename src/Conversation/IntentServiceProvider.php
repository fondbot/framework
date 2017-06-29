<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class IntentServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        IntentManager::class,
    ];

    /**
     * Define intents.
     *
     * @return string[]
     */
    abstract public function intents(): array;

    /**
     * Define fallback intent.
     *
     * @return string
     */
    abstract public function fallbackIntent(): string;

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function register(): void
    {
        $this->container->share(IntentManager::class, function () {
            $manager = new IntentManager();

            foreach ($this->intents() as $intent) {
                $manager->add($this->container->get($intent));
            }

            $manager->setFallbackIntent($this->container->get($this->fallbackIntent()));

            return $manager;
        });
    }
}
