<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Contracts\Cache;
use FondBot\Conversation\SessionManager;
use FondBot\Conversation\SessionServiceProvider;

class SessionServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $this->container->add(Cache::class, $this->mock(Cache::class));

        $this->container->addServiceProvider(new SessionServiceProvider());

        $this->assertInstanceOf(SessionManager::class, $this->container->get(SessionManager::class));
    }
}
