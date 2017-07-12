<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use Mockery;
use Monolog\Logger;
use FondBot\Http\Request;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Context;
use FondBot\Conversation\Session;
use FondBot\Drivers\DriverManager;
use FondBot\Channels\ChannelManager;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Conversable;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\ContextManager;
use FondBot\Conversation\SessionManager;
use FondBot\Conversation\ConversationManager;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Extensions\WebhookVerification;

class ConversationManagerTest extends TestCase
{
    public function test_handle_new_dialog(): void
    {
        $request = $this->mock(Request::class);
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $channelManager = $this->mock(ChannelManager::class);
        $driverManager = $this->mock(DriverManager::class);
        $sessionManager = $this->mock(SessionManager::class);
        $contextManager = $this->mock(ContextManager::class);
        $intentManager = $this->mock(IntentManager::class);
        $session = $this->mock(Session::class);
        $context = $this->mock(Context::class);
        $intent = $this->mock(Intent::class);
        $receivedMessage = $this->mock(ReceivedMessage::class);
        $channelName = $this->faker()->userName;

        $channelManager->shouldReceive('create')->with($channelName)->andReturn($channel)->once();
        $driverManager->shouldReceive('get')->with($channel, $request)->andReturn($driver)->once();

        $driver->shouldReceive('verifyRequest')->once();
        $sessionManager->shouldReceive('load')->andReturn($session)->once();
        $contextManager->shouldReceive('load')->andReturn($context)->once();

        $session->shouldReceive('getInteraction')->andReturn(null)->once();

        $driver->shouldReceive('getMessage')->andReturn($receivedMessage)->once();
        $intentManager->shouldReceive('find')
            ->with($receivedMessage)
            ->andReturn($intent)
            ->once();

        $session->shouldReceive('setIntent')->with($intent)->once();
        $session->shouldReceive('setInteraction')->with(null)->once();
        $intent->shouldReceive('handle')->with($this->kernel)->once();

        $sessionManager->shouldReceive('close')->once();
        $contextManager->shouldReceive('clear')->once();

        (new ConversationManager)->handle($channelName, $request);

        $this->assertSame($driver, $this->kernel->getDriver());
    }

    public function test_handle_existing_dialog(): void
    {
        $request = $this->mock(Request::class);
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $channelManager = $this->mock(ChannelManager::class);
        $driverManager = $this->mock(DriverManager::class);
        $sessionManager = $this->mock(SessionManager::class);
        $contextManager = $this->mock(ContextManager::class);
        $intentManager = $this->mock(IntentManager::class);
        $interaction = $this->mock(Interaction::class);
        $session = $this->mock(Session::class);
        $context = $this->mock(Context::class);
        $channelName = $this->faker()->userName;

        $channelManager->shouldReceive('create')->with($channelName)->andReturn($channel)->once();
        $driverManager->shouldReceive('get')->with($channel, $request)->andReturn($driver)->once();

        $driver->shouldReceive('verifyRequest')->once();
        $sessionManager->shouldReceive('load')->andReturn($session)->once();
        $contextManager->shouldReceive('load')->andReturn($context)->once();

        $session->shouldReceive('getInteraction')->andReturn($interaction)->atLeast()->once();

        $intentManager->shouldReceive('find')->never();
        $interaction->shouldReceive('handle')->with($this->kernel)->once();

        $sessionManager->shouldReceive('close')->once();
        $contextManager->shouldReceive('clear')->once();

        (new ConversationManager)->handle($channelName, $request);
    }

    public function test_handle_invalid_request(): void
    {
        $request = $this->mock(Request::class);
        $channel = $this->mock(Channel::class);
        $driver = $this->mock(Driver::class);
        $channelManager = $this->mock(ChannelManager::class);
        $driverManager = $this->mock(DriverManager::class);

        $channelManager->shouldReceive('create')->with('foo')->andReturn($channel)->once();
        $driverManager->shouldReceive('get')->with($channel, $request)->andReturn($driver)->once();
        $this->mock(Logger::class)->shouldReceive('warning');

        $driver->shouldReceive('verifyRequest')->andThrow(new InvalidRequest('Invalid request.'));

        $this->assertSame('', (new ConversationManager)->handle('foo', $request));
    }

    public function test_handle_webhook_verification(): void
    {
        $request = $this->mock(Request::class);
        $channel = $this->mock(Channel::class);
        /** @var \Mockery\Mock|mixed $driver */
        $driver = Mockery::mock(Driver::class, WebhookVerification::class);
        $channelManager = $this->mock(ChannelManager::class);
        $driverManager = $this->mock(DriverManager::class);
        $verification = $this->faker()->uuid;

        $channelManager->shouldReceive('create')->with('foo')->andReturn($channel)->once();
        $driverManager->shouldReceive('get')->with($channel, $request)->andReturn($driver)->once();

        $driver->shouldReceive('isVerificationRequest')->andReturn(true);
        $driver->shouldReceive('verifyWebhook')->andReturn($verification);

        $result = (new ConversationManager())->handle('foo', $request);

        $this->assertSame($verification, $result);
    }

    public function test_restart_intent(): void
    {
        $intent = $this->mock(Intent::class);
        $session = $this->mock(Session::class);

        $this->kernel->setSession($session);

        $session->shouldReceive('setIntent')->with($intent)->once();
        $session->shouldReceive('setInteraction')->with(null)->once();
        $intent->shouldReceive('handle')->once();

        (new ConversationManager)->restart($intent);
    }

    public function test_transition(): void
    {
        $conversable = $this->mock(Conversable::class);
        $conversable->shouldReceive('handle')->with($this->kernel)->once();

        (new ConversationManager)->transition($conversable);
    }

    public function test_restart_interaction(): void
    {
        $interaction = $this->mock(Interaction::class);
        $session = $this->mock(Session::class);

        $this->kernel->setSession($session);

        $session->shouldReceive('setInteraction')->with(null)->once();
        $interaction->shouldReceive('handle')->once();

        (new ConversationManager)->restart($interaction);
    }
}
