<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation;

use FondBot\Tests\TestCase;
use Psr\SimpleCache\CacheInterface;
use Cache\Adapter\PHPArray\ArrayCachePool;
use FondBot\Foundation\CacheServiceProvider;

class CacheServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $adapter = $this->mock(ArrayCachePool::class)->makePartial();
        $provider = $this->mock(CacheServiceProvider::class)->makePartial();

        $provider->shouldReceive('adapter')->andReturn($adapter)->once();

        $this->container->addServiceProvider($provider);

        $this->assertSame($adapter, $this->container->get(CacheInterface::class));
    }
}
