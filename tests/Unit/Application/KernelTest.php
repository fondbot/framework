<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Application\Kernel;
use FondBot\Conversation\Session;
use FondBot\Conversation\SessionManager;

class KernelTest extends TestCase
{
    public function test_getInstance(): void
    {
        $kernel = Kernel::createInstance($this->container);

        $this->assertSame($kernel, Kernel::getInstance());
    }

    public function test_channel(): void
    {
        $channel = $this->mock(Channel::class);
        $this->kernel->setChannel($channel);

        $this->assertSame($channel, $this->kernel->getChannel());
    }

    public function test_driver(): void
    {
        $driver = $this->mock(Driver::class);
        $this->kernel->setDriver($driver);

        $this->assertSame($driver, $this->kernel->getDriver());
    }

    public function test_session(): void
    {
        $this->kernel->setSession($session = $this->mock(Session::class));

        $this->assertSame($session, $this->kernel->getSession());
    }

    public function test_loadSession(): void
    {
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $session = $this->mock(Session::class);
        $sessionManager = $this->mock(SessionManager::class);

        $channel->shouldReceive('getName')->andReturn('foo')->once();
        $sessionManager->shouldReceive('load')->with('foo', $driver)->andReturn($session)->once();

        $this->kernel->loadSession($channel, $driver);

        $this->assertSame($session, $this->kernel->getSession());
    }

    public function test_saveSession(): void
    {
        $sessionManager = $this->mock(SessionManager::class);

        $this->kernel->setSession($session = $this->mock(Session::class));

        $sessionManager->shouldReceive('save')->with($session)->once();

        $this->kernel->saveSession();
    }

    public function test_closeSession(): void
    {
        $sessionManager = $this->mock(SessionManager::class);

        $this->kernel->setSession($session = $this->mock(Session::class));

        $sessionManager->shouldReceive('close')->with($session)->once();

        $this->kernel->closeSession();

        $this->assertNull($this->kernel->getSession());
    }
}
