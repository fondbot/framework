<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Tests\TestCase;
use Psr\SimpleCache\CacheInterface;
use Cache\Adapter\PHPArray\ArrayCachePool;
use FondBot\Application\CacheServiceProvider;

class CacheServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $adapter = $this->mock(ArrayCachePool::class);
        $provider = $this->mock(CacheServiceProvider::class)->makePartial();

        $provider->shouldReceive('adapter')->andReturn($adapter)->once();

        $this->container->addServiceProvider($provider);

        $this->assertSame($adapter, $this->container->get(CacheInterface::class));
    }
}
