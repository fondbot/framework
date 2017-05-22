<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use Monolog\Logger;
use FondBot\Tests\TestCase;
use Psr\Log\LoggerInterface;
use Monolog\Handler\HandlerInterface;
use FondBot\Application\LogServiceProvider;

class LogServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(LogServiceProvider::class)->makePartial();
        $provider->shouldReceive('handlers')->andReturn([
            $handler = $this->mock(HandlerInterface::class),
        ]);

        $this->container->addServiceProvider($provider);

        $this->assertInstanceOf(Logger::class, $this->container->get(LoggerInterface::class));
        $this->assertCount(1, $this->container->get(Logger::class)->getHandlers());
        $this->assertSame($handler, $this->container->get(Logger::class)->getHandlers()[0]);
    }
}
