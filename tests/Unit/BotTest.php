<?php

declare(strict_types=1);

namespace Tests\Unit;

use Mockery;
use FondBot\Bot;
use Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Conversation\Story;
use FondBot\Conversation\Context;
use FondBot\Contracts\Channels\User;
use FondBot\Contracts\Channels\Driver;
use FondBot\Conversation\StoryManager;
use FondBot\Conversation\ContextManager;
use FondBot\Contracts\Conversation\Keyboard;
use FondBot\Contracts\Channels\ReceivedMessage;
use FondBot\Channels\Exceptions\InvalidChannelRequest;
use FondBot\Contracts\Channels\Extensions\WebhookVerification;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface contextManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface storyManager
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface driver
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface channel
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface context
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface receivedMessage
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface story
 */
class BotTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->contextManager = $this->mock(ContextManager::class);
        $this->storyManager = $this->mock(StoryManager::class);
        $this->driver = $this->mock(Driver::class);
        $this->channel = $this->mock(Channel::class);
        $this->context = $this->mock(Context::class);
        $this->receivedMessage = $this->mock(ReceivedMessage::class);
        $this->story = $this->mock(Story::class);

        Bot::createInstance($this->container, $this->channel, $this->driver, [], []);
    }

    public function test_context()
    {
        Bot::getInstance()->setContext($this->context);
        $this->assertSame($this->context, Bot::getInstance()->getContext());
    }

    public function test_process_without_verification()
    {
        $bot = Bot::getInstance();

        $this->channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $this->driver->shouldReceive('verifyRequest')->once();
        $this->contextManager->shouldReceive('resolve')
            ->with($channelName, $this->driver)
            ->andReturn($this->context)
            ->once();

        $this->driver->shouldReceive('getMessage')->andReturn($this->receivedMessage)->once();
        $this->storyManager->shouldReceive('find')
            ->with($this->context, $this->receivedMessage)
            ->andReturn($this->story)
            ->once();

        $this->context->shouldReceive('setStory')->with($this->story)->once();
        $this->context->shouldReceive('setInteraction')->with(null)->once();
        $this->context->shouldReceive('setValues')->with([])->once();
        $this->story->shouldReceive('handle')->with($bot)->once();
        $this->contextManager->shouldReceive('save')->with($this->context)->once();

        $bot->process();
    }

    public function test_process_invalid_request()
    {
        $bot = Bot::getInstance();

        $this->channel->shouldReceive('getName')->andReturn($channelName = $this->faker()->userName);
        $this->driver->shouldReceive('verifyRequest')->andThrow(new InvalidChannelRequest('Invalid request.'));

        $this->assertSame('Invalid request.', $bot->process());
    }

    public function test_process_with_webhook_verification()
    {
        $this->driver = Mockery::mock(Driver::class, WebhookVerification::class);
        Bot::createInstance($this->container, $this->channel, $this->driver, [], []);

        $request = ['verification' => str_random()];
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

        Bot::getInstance()->sendMessage($recipient, $text, $keyboard);
    }
}
