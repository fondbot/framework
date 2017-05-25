<?php

declare(strict_types=1);

namespace FondBot\Conversation;

use League\Container\ServiceProvider\AbstractServiceProvider;

class ConversationServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        ConversationManager::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $this->container->share(ConversationManager::class, function () {
            return new ConversationManager;
        });
    }
}
