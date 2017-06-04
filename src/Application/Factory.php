<?php

declare(strict_types=1);

namespace FondBot\Application;

use Http\Client\HttpClient;
use League\Container\Container;
use Http\Message\RequestFactory;
use Http\Discovery\HttpClientDiscovery;
use League\Container\ReflectionContainer;
use FondBot\Drivers\DriverServiceProvider;
use Http\Discovery\MessageFactoryDiscovery;
use FondBot\Conversation\SessionServiceProvider;
use FondBot\Conversation\ConversationServiceProvider;

class Factory
{
    public static function create(Container $container): Kernel
    {
        $container->delegate(new ReflectionContainer);

        $container->share(HttpClient::class, HttpClientDiscovery::find());
        $container->share(RequestFactory::class, MessageFactoryDiscovery::find());

        // Load service providers
        $container->addServiceProvider(new DriverServiceProvider);
        $container->addServiceProvider(new SessionServiceProvider);
        $container->addServiceProvider(new ConversationServiceProvider);

        // Boot kernel
        $kernel = Kernel::createInstance($container);

        $container->add(Kernel::class, $kernel);

        return $kernel;
    }
}
