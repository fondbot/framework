<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation;

use Mockery\Mock;
use Monolog\Logger;
use FondBot\Tests\TestCase;
use Psr\Log\LoggerInterface;
use Monolog\Handler\HandlerInterface;
use FondBot\Foundation\LogServiceProvider;

class LogServiceProviderTest extends TestCase
{
    public function test(): void
    {
        /** @var Mock|LogServiceProvider $provider */
        $provider = $this->mock(LogServiceProvider::class)->makePartial();
        $provider->shouldReceive('handlers')->andReturn([
            $handler = $this->mock(HandlerInterface::class),
        ]);
        $provider->register();

        $this->container->addServiceProvider($provider);

        $this->assertInstanceOf(Logger::class, $this->container->get(LoggerInterface::class));
        $this->assertCount(1, $this->container->get(Logger::class)->getHandlers());
        $this->assertSame($handler, $this->container->get(Logger::class)->getHandlers()[0]);
    }
}
