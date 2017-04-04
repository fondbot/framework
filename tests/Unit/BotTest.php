<?php

declare(strict_types=1);

namespace Tests\Unit;

use Mockery;
use FondBot\Bot;
use Tests\TestCase;
use FondBot\Helpers\Str;
use FondBot\Channels\Channel;
use FondBot\Conversation\Context;
use FondBot\Contracts\Drivers\User;
use FondBot\Contracts\Drivers\Driver;
use FondBot\Conversation\IntentManager;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Conversation\Intent;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Drivers\OutgoingMessage;
use FondBot\Contracts\Drivers\ReceivedMessage;
use FondBot\Contracts\Conversation\Conversable;
use FondBot\Contracts\Conversation\Interaction;
use FondBot\Contracts\Drivers\InvalidRequest;
use FondBot\Contracts\Drivers\Extensions\WebhookVerification;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface contextManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $intentManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface channel
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface context
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface receivedMessage
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $intent
 * @property mixed|Mockery\Mock                         interaction
 */
class BotTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->contextManager = $this->mock(ContextManager::class);
        $this->intentManager = $this->mock(IntentManager::class);
        $this->driver = $this->mock(Driver::class);
        $this->channel = $this->mock(Channel::class);
        $this->context = $this->mock(Context::class);
        $this->receivedMessage = $this->mock(ReceivedMessage::class);
        $this->intent = Mockery::mock(Intent::class, Conversable::class);
        $this->interaction = Mockery::mock(Interaction::class, Conversable::class);

        Bot::createInstance($this->container, $this->channel, $this->driver, [], []);
    }

    public function test_context()
    {
        Bot::getInstance()->setContext($this->context);
        $this->assertSame($this->context, Bot::getInstance()->getContext());
    }

    public function test_clearContext()
    {
        Bot::getInstance()->setContext($this->context);

        $this->contextManager->shouldReceive('clear')->with($this->context)->once();

        Bot::getInstance()->clearContext();
        $this->assertNull(Bot::getInstance()->getContext());
    }

    public function test_process_new_dialog()
    {
        $bot = Bot::getInstance();

        $this->channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $this->driver->shouldReceive('verifyRequest')->once();
        $this->contextManager->shouldReceive('resolve')
            ->with($channelName, $this->driver)
            ->andReturn($this->context)
            ->once();

        $this->context->shouldReceive('getInteraction')->andReturn(null)->once();

        $this->driver->shouldReceive('getMessage')->andReturn($this->receivedMessage)->once();
        $this->intentManager->shouldReceive('find')
            ->with($this->receivedMessage)
            ->andReturn($this->intent)
            ->once();

        $this->context->shouldReceive('setIntent')->with($this->intent)->once();
        $this->context->shouldReceive('setInteraction')->with(null)->once();
        $this->context->shouldReceive('setValues')->with([])->once();
        $this->intent->shouldReceive('handle')->with($bot)->once();
        $this->contextManager->shouldReceive('save')->with($this->context)->once();

        $bot->process();
    }

    public function test_process_continue_dialog()
    {
        $bot = Bot::getInstance();

        $this->channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $this->driver->shouldReceive('verifyRequest')->once();
        $this->contextManager->shouldReceive('resolve')
            ->with($channelName, $this->driver)
            ->andReturn($this->context)
            ->once();

        $this->context->shouldReceive('getInteraction')->andReturn($this->interaction)->atLeast()->once();

        $this->intentManager->shouldReceive('find')->never();
        $this->interaction->shouldReceive('handle')->with($bot)->once();

        $this->contextManager->shouldReceive('save')->with($this->context)->once();

        $bot->process();
    }

    public function test_process_invalid_request()
    {
        $bot = Bot::getInstance();

        $this->channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $this->driver->shouldReceive('verifyRequest')->andThrow(new InvalidRequest('Invalid request.'));

        $this->assertSame('Invalid request.', $bot->process());
    }

    public function test_process_with_webhook_verification()
    {
        $this->driver = Mockery::mock(Driver::class, WebhookVerification::class);
        Bot::createInstance($this->container, $this->channel, $this->driver, [], []);

        $request = ['verification' => Str::random()];
        $bot = Bot::getInstance();

        $this->channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $this->driver->shouldReceive('isVerificationRequest')->andReturn(true);
        $this->driver->shouldReceive('verifyWebhook')->andReturn($request['verification']);

        $result = $bot->process();

        $this->assertSame($request['verification'], $result);
    }

    public function test_sendMessage()
    {
        $recipient = $this->mock(User::class);
        $text = $this->faker()->text;
        $keyboard = $this->mock(Keyboard::class);

        $this->driver->shouldReceive('sendMessage')->with($recipient, $text, $keyboard)->once();

        $result = Bot::getInstance()->sendMessage($recipient, $text, $keyboard);
        $this->assertInstanceOf(OutgoingMessage::class, $result);
    }

    public function test_sendMessage_driver_does_not_match()
    {
        $recipient = $this->mock(User::class);
        $text = $this->faker()->text;
        $keyboard = $this->mock(Keyboard::class);

        $this->driver->shouldReceive('sendMessage')->never();

        $result = Bot::getInstance()->sendMessage($recipient, $text, $keyboard, 'some-driver');
        $this->assertNull($result);
    }
}
