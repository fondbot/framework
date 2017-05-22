<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Cache;

use FondBot\Cache\Adapter;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Cache;
use FondBot\Cache\CacheServiceProvider;

class CacheServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(CacheServiceProvider::class)->makePartial();
        $provider->shouldReceive('adapter')->andReturn($adapter = $this->mock(Adapter::class))->once();

        $this->container->addServiceProvider($provider);

        $this->assertSame($adapter, $this->container->get(Cache::class));
    }
}
