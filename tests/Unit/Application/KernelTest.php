<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use Mockery;
use FondBot\Http\Request;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Application\Kernel;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Session;
use FondBot\Drivers\DriverManager;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\SessionManager;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Extensions\WebhookVerification;

class KernelTest extends TestCase
{
    public function test_session(): void
    {
        $kernel = new Kernel($this->container);
        $kernel->setSession($session = $this->mock(Session::class));

        $this->assertSame($session, $kernel->getSession());
    }

    public function test_clearSession(): void
    {
        $kernel = new Kernel($this->container);
        $kernel->setSession($session = $this->mock(Session::class));

        $this->container->add(SessionManager::class, $sessionManager = $this->mock(SessionManager::class));

        $sessionManager->shouldReceive('clear')->with($session)->once();

        $kernel->clearSession();

        $this->assertNull($kernel->getSession());
    }

    public function test_process_new_dialog(): void
    {
        $kernel = new Kernel($this->container);
        $request = $this->mock(Request::class);
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);

        $this->container->add(DriverManager::class, $driverManager = $this->mock(DriverManager::class));
        $this->container->add(SessionManager::class, $sessionManager = $this->mock(SessionManager::class));
        $this->container->add(IntentManager::class, $intentManager = $this->mock(IntentManager::class));

        $driverManager->shouldReceive('get')->with($channel, $request)->andReturn($driver)->once();

        $channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName)->atLeast()->once();
        $driver->shouldReceive('verifyRequest')->once();
        $sessionManager->shouldReceive('resolve')
            ->with($channelName, $driver)
            ->andReturn($session = $this->mock(Session::class))
            ->once();

        $session->shouldReceive('getInteraction')->andReturn(null)->once();

        $driver->shouldReceive('getMessage')->andReturn($receivedMessage = $this->mock(ReceivedMessage::class))->once();
        $intentManager->shouldReceive('find')
            ->with($receivedMessage)
            ->andReturn($intent = $this->mock(Intent::class))
            ->once();

        $session->shouldReceive('setIntent')->with($intent)->once();
        $session->shouldReceive('setInteraction')->with(null)->once();
        $session->shouldReceive('setValues')->with([])->once();
        $intent->shouldReceive('handle')->with($kernel)->once();
        $sessionManager->shouldReceive('save')->with($session)->once();

        $kernel->process($channel, $request);

        $this->assertSame($driver, $kernel->getDriver());
    }

    public function test_process_continue_dialog(): void
    {
        $kernel = new Kernel($this->container);
        $request = $this->mock(Request::class);
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);

        $this->container->add(DriverManager::class, $driverManager = $this->mock(DriverManager::class));
        $this->container->add(SessionManager::class, $sessionManager = $this->mock(SessionManager::class));
        $this->container->add(IntentManager::class, $intentManager = $this->mock(IntentManager::class));

        $driverManager->shouldReceive('get')->with($channel, $request)->andReturn($driver)->once();

        $channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName)->atLeast()->once();
        $driver->shouldReceive('verifyRequest')->once();
        $sessionManager->shouldReceive('resolve')
            ->with($channelName, $driver)
            ->andReturn($session = $this->mock(Session::class))
            ->once();

        $session->shouldReceive('getInteraction')->andReturn($interaction = $this->mock(Interaction::class))->atLeast()->once();

        $intentManager->shouldReceive('find')->never();
        $interaction->shouldReceive('handle')->with($kernel)->once();

        $sessionManager->shouldReceive('save')->with($session)->once();

        $kernel->process($channel, $request);
    }

    public function test_process_invalid_request(): void
    {
        $kernel = new Kernel($this->container);
        $request = $this->mock(Request::class);
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);

        $this->container->add(DriverManager::class, $driverManager = $this->mock(DriverManager::class));
        $driverManager->shouldReceive('get')->with($channel, $request)->andReturn($driver)->once();

        $driver->shouldReceive('verifyRequest')->andThrow(new InvalidRequest('Invalid request.'));

        $this->assertSame('Invalid request.', $kernel->process($channel, $request));
    }

    public function test_process_with_webhook_verification(): void
    {
        $kernel = new Kernel($this->container);
        $request = $this->mock(Request::class);
        $channel = $this->mock(Channel::class);
        /** @var Mockery\Mock|mixed $driver */
        $driver = Mockery::mock(Driver::class, WebhookVerification::class);
        $verification = $this->faker()->uuid;

        $this->container->add(DriverManager::class, $driverManager = $this->mock(DriverManager::class));
        $driverManager->shouldReceive('get')->with($channel, $request)->andReturn($driver)->once();

        $driver->shouldReceive('isVerificationRequest')->andReturn(true);
        $driver->shouldReceive('verifyWebhook')->andReturn($verification);

        $result = $kernel->process($channel, $request);

        $this->assertSame($verification, $result);
    }
}
