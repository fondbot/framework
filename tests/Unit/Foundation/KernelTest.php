<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation;

use FondBot\Tests\TestCase;
use FondBot\Foundation\Kernel;
use FondBot\Conversation\Context;
use FondBot\Conversation\Session;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\SessionManager;

/**
 * @property mixed|\Mockery\Mock contextManager
 * @property mixed|\Mockery\Mock sessionManager
 */
class KernelTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        $this->contextManager = $this->mock(ContextManager::class);
        $this->sessionManager = $this->mock(SessionManager::class);
    }

    public function testCloseSession(): void
    {
        $session = $this->mock(Session::class);
        $sessionManager = $this->mock(SessionManager::class);

        $sessionManager->shouldReceive('close')->with($session)->once();

        $kernel = new Kernel($this->container);
        $kernel->setSession($session);
        $kernel->closeSession();

        $this->assertNull($kernel->getSession());
    }

    public function testClearContext() : void
    {
        $context = $this->mock(Context::class);
        $this->contextManager->shouldReceive('clear')->once();

        $kernel = new Kernel($this->container);
        $kernel->setContext($context);
        $kernel->clearContext($context);

        $this->assertNull($kernel->getContext());
    }
}
