<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Tests\TestCase;
use League\Route\RouteCollection;
use Psr\Http\Message\ServerRequestInterface;
use FondBot\Application\RouteServiceProvider;

class RouteServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(RouteServiceProvider::class)->makePartial();
        $this->container->addServiceProvider($provider);

        $provider->shouldReceive('routes')->once();

        $this->assertInstanceOf(ServerRequestInterface::class, $this->container->get('request'));
        $this->assertInstanceOf(RouteCollection::class, $this->container->get('router'));
    }
}
