<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use Psr\SimpleCache\CacheInterface;
use FondBot\Conversation\SessionManager;
use Cache\Adapter\PHPArray\ArrayCachePool;
use FondBot\Conversation\SessionServiceProvider;

class SessionServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $this->container->add(CacheInterface::class, $this->mock(ArrayCachePool::class));

        $this->container->addServiceProvider(new SessionServiceProvider());

        $this->assertInstanceOf(SessionManager::class, $this->container->get(SessionManager::class));
    }
}
