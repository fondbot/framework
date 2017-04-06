<?php

declare(strict_types=1);

namespace Tests\Unit;

use Mockery;
use FondBot\Bot;
use Tests\TestCase;
use FondBot\Drivers\Driver;
use FondBot\Channels\Channel;
use FondBot\Conversation\Intent;
use FondBot\Conversation\Context;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Conversation\Interaction;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\ContextManager;
use FondBot\Drivers\Exceptions\InvalidRequest;
use FondBot\Drivers\Extensions\WebhookVerification;

class BotTest extends TestCase
{
    public function test_context_and_driver()
    {
        Bot::createInstance($this->container, $this->mock(Channel::class), $driver = $this->mock(Driver::class));

        Bot::getInstance()->setContext($context = $this->mock(Context::class));

        $this->assertSame($context, Bot::getInstance()->getContext());
        $this->assertSame($driver, Bot::getInstance()->getDriver());
    }

    public function test_clearContext()
    {
        Bot::createInstance($this->container, $this->mock(Channel::class), $this->mock(Driver::class));

        Bot::getInstance()->setContext($context = $this->mock(Context::class));

        $this->container->bind(ContextManager::class, $contextManager = $this->mock(ContextManager::class));

        $contextManager->shouldReceive('clear')->with($context)->once();

        Bot::getInstance()->clearContext();
        $this->assertNull(Bot::getInstance()->getContext());
    }

    public function test_process_new_dialog()
    {
        Bot::createInstance(
            $this->container,
            $channel = $this->mock(Channel::class),
            $driver = $this->mock(Driver::class)
        );

        $bot = Bot::getInstance();

        $this->container->bind(ContextManager::class, $contextManager = $this->mock(ContextManager::class));
        $this->container->bind(IntentManager::class, $intentManager = $this->mock(IntentManager::class));

        $channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $driver->shouldReceive('verifyRequest')->once();
        $contextManager->shouldReceive('resolve')
            ->with($channelName, $driver)
            ->andReturn($context = $this->mock(Context::class))
            ->once();

        $context->shouldReceive('getInteraction')->andReturn(null)->once();

        $driver->shouldReceive('getMessage')->andReturn($receivedMessage = $this->mock(ReceivedMessage::class))->once();
        $intentManager->shouldReceive('find')
            ->with($receivedMessage)
            ->andReturn($intent = $this->mock(Intent::class))
            ->once();

        $context->shouldReceive('setIntent')->with($intent)->once();
        $context->shouldReceive('setInteraction')->with(null)->once();
        $context->shouldReceive('setValues')->with([])->once();
        $intent->shouldReceive('handle')->with($bot)->once();
        $contextManager->shouldReceive('save')->with($context)->once();

        $bot->process();
    }

    public function test_process_continue_dialog()
    {
        Bot::createInstance(
            $this->container,
            $channel = $this->mock(Channel::class),
            $driver = $this->mock(Driver::class)
        );

        $bot = Bot::getInstance();

        $this->container->bind(ContextManager::class, $contextManager = $this->mock(ContextManager::class));
        $this->container->bind(IntentManager::class, $intentManager = $this->mock(IntentManager::class));

        $channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $driver->shouldReceive('verifyRequest')->once();
        $contextManager->shouldReceive('resolve')
            ->with($channelName, $driver)
            ->andReturn($context = $this->mock(Context::class))
            ->once();

        $context->shouldReceive('getInteraction')->andReturn($interaction = $this->mock(Interaction::class))->atLeast()->once();

        $intentManager->shouldReceive('find')->never();
        $interaction->shouldReceive('handle')->with($bot)->once();

        $contextManager->shouldReceive('save')->with($context)->once();

        $bot->process();
    }

    public function test_process_invalid_request()
    {
        Bot::createInstance(
            $this->container,
            $channel = $this->mock(Channel::class),
            $driver = $this->mock(Driver::class)
        );

        $bot = Bot::getInstance();

        $channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $driver->shouldReceive('verifyRequest')->andThrow(new InvalidRequest('Invalid request.'));

        $this->assertSame('Invalid request.', $bot->process());
    }

    public function test_process_with_webhook_verification()
    {
        /** @var Mockery\Mock|mixed $driver */
        $driver = Mockery::mock(Driver::class, WebhookVerification::class);

        Bot::createInstance($this->container, $channel = $this->mock(Channel::class), $driver);

        $request = ['verification' => $this->faker()->sha1];
        $bot = Bot::getInstance();

        $channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $driver->shouldReceive('isVerificationRequest')->andReturn(true);
        $driver->shouldReceive('verifyWebhook')->andReturn($request['verification']);

        $result = $bot->process();

        $this->assertSame($request['verification'], $result);
    }
}
