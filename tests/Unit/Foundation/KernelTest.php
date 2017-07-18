<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation;

use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Foundation\Kernel;
use FondBot\Conversation\Context;
use FondBot\Conversation\Session;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\SessionManager;

class KernelTest extends TestCase
{
    /**
     * @var ContextManager
     */
    protected $contextManager;

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    public function setUp() : void
    {
        parent::setUp();
        $this->contextManager = $this->mock(ContextManager::class);
        $this->sessionManager = $this->mock(SessionManager::class);
        $this->container->share(ContextManager::class, $this->contextManager);
        $this->container->share(SessionManager::class, $this->sessionManager);
    }

    public function testGetInstance(): void
    {
        $kernel = Kernel::createInstance($this->container);

        $this->assertSame($kernel, Kernel::getInstance());
    }

    public function testChannel(): void
    {
        $channel = $this->mock(Channel::class);
        $this->kernel->setChannel($channel);

        $this->assertSame($channel, $this->kernel->getChannel());
    }

    public function testDriver(): void
    {
        $driver = $this->mock(Driver::class);
        $this->kernel->setDriver($driver);

        $this->assertSame($driver, $this->kernel->getDriver());
    }

    public function testSession(): void
    {
        $this->kernel->setSession($session = $this->mock(Session::class));

        $this->assertSame($session, $this->kernel->getSession());
    }

    public function testBoot(): void
    {
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $session = $this->mock(Session::class);
        $context = $this->mock(Context::class);
        $sessionManager = $this->mock(SessionManager::class);
        $contextManager = $this->mock(ContextManager::class);

        $sessionManager->shouldReceive('load')->with($channel, $driver)->andReturn($session)->once();
        $contextManager->shouldReceive('load')->with($channel, $driver)->andReturn($context)->once();

        $this->kernel->boot($channel, $driver);

        $this->assertSame($session, $this->kernel->getSession());
        $this->assertSame($context, $this->kernel->getContext());
    }

    public function testCloseSession(): void
    {
        $sessionManager = $this->mock(SessionManager::class);

        $this->kernel->setSession($session = $this->mock(Session::class));

        $sessionManager->shouldReceive('close')->with($session)->once();

        $this->kernel->closeSession();

        $this->assertNull($this->kernel->getSession());
    }

    public function testClearContext() : void
    {
        $context = $this->mock(Context::class);
        $this->contextManager->shouldReceive('clear')->once();
        $this->kernel->setContext($context);
        $this->kernel->clearContext($context);
    }

    public function testTerminate() : void
    {
        $this->kernel->terminate();
    }

    public function testTerminateSessionManager() : void
    {
        $kernel = Kernel::createInstance($this->container, true);
        $session = $this->mock(Session::class);

        $this->sessionManager->shouldReceive('save')->once();
        $kernel->setSession($session);
        $kernel->terminate();
    }

    public function testContextManager() : void
    {
        $kernel = Kernel::createInstance($this->container, true);
        $context = $this->mock(Context::class);

        $this->contextManager->shouldReceive('save')->once();
        $kernel->setContext($context);
        $kernel->terminate();
    }
}
