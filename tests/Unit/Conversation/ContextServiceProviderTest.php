<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use FondBot\Contracts\Cache;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\ContextServiceProvider;

class ContextServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $this->container->add(Cache::class, $this->mock(Cache::class));

        $this->container->addServiceProvider(new ContextServiceProvider());

        $this->assertInstanceOf(ContextManager::class, $this->container->get(ContextManager::class));
    }
}
