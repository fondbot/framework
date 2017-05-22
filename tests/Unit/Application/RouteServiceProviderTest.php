<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Tests\TestCase;
use FondBot\Application\Kernel;
use League\Route\RouteCollection;
use Psr\Http\Message\ServerRequestInterface;
use FondBot\Application\RouteServiceProvider;

class RouteServiceProviderTest extends TestCase
{
    public function test_without_prefix(): void
    {
        $this->container->share(Kernel::class, Kernel::createInstance($this->container));
        $this->container->addServiceProvider(new RouteServiceProvider(''));

        $this->assertInstanceOf(ServerRequestInterface::class, $this->container->get('request'));
        $this->assertInstanceOf(RouteCollection::class, $this->container->get('router'));
    }

    public function test_with_prefix(): void
    {
        $prefix = 'foo';

        $this->container->share(Kernel::class, Kernel::createInstance($this->container));
        $this->container->addServiceProvider(new RouteServiceProvider($prefix));

        $this->assertInstanceOf(ServerRequestInterface::class, $this->container->get('request'));
        $this->assertInstanceOf(RouteCollection::class, $this->container->get('router'));
    }
}
