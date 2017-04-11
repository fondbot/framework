<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Traits;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Queue\Queue;
use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Conversation\Keyboard;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Drivers\ReceivedMessage\Attachment;

class SendsMessagesTest extends TestCase
{
    public function test_sendMessage()
    {
        $bot = $this->mock(Bot::class);

        $bot->shouldReceive('get')->with(Queue::class)->andReturn($queue = $this->mock(Queue::class));
        $bot->shouldReceive('getDriver')->once();
        $queue->shouldReceive('push')->once();

        $class = new SendsMessagesTraitTestClass($bot, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendMessage($this->faker()->text, $this->mock(Keyboard::class));
    }

    public function test_sendDelayedMessage()
    {
        $bot = $this->mock(Bot::class);

        $bot->shouldReceive('get')->with(Queue::class)->andReturn($queue = $this->mock(Queue::class));
        $bot->shouldReceive('getDriver')->once();
        $queue->shouldReceive('later')->once();

        $class = new SendsMessagesTraitTestClass($bot, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendDelayedMessage(random_int(1, 10), $this->faker()->text, $this->mock(Keyboard::class));
    }

    public function test_sendAttachment()
    {
        $bot = $this->mock(Bot::class);

        $bot->shouldReceive('get')->with(Queue::class)->andReturn($queue = $this->mock(Queue::class));
        $bot->shouldReceive('getDriver')->once();
        $queue->shouldReceive('push')->once();

        $class = new SendsMessagesTraitTestClass($bot, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendAttachment($this->mock(Attachment::class));
    }

    public function test_sendAttachment_with_delay()
    {
        $bot = $this->mock(Bot::class);

        $bot->shouldReceive('get')->with(Queue::class)->andReturn($queue = $this->mock(Queue::class));
        $bot->shouldReceive('getDriver')->once();
        $queue->shouldReceive('later')->once();

        $class = new SendsMessagesTraitTestClass($bot, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendAttachment($this->mock(Attachment::class), random_int(1, 10));
    }
}

class SendsMessagesTraitTestClass
{
    use SendsMessages;

    protected $bot;
    private $chat;
    private $user;

    public function __construct(Bot $bot, Chat $chat, User $user)
    {
        $this->bot = $bot;
        $this->chat = $chat;
        $this->user = $user;
    }

    public function __call($name, $arguments)
    {
        return $this->$name(...$arguments);
    }

    /**
     * Get chat.
     *
     * @return Chat
     */
    protected function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * Get user.
     *
     * @return User
     */
    protected function getUser(): User
    {
        return $this->user;
    }
}
