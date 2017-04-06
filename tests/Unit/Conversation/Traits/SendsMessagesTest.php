<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Traits;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Queue\Queue;
use FondBot\Drivers\User;
use FondBot\Conversation\Keyboard;
use FondBot\Conversation\Traits\SendsMessages;

class SendsMessagesTest extends TestCase
{
    public function test_sendMessage()
    {
        $bot = $this->mock(Bot::class);

        $bot->shouldReceive('get')->with(Queue::class)->andReturn($queue = $this->mock(Queue::class));
        $bot->shouldReceive('getDriver');
        $queue->shouldReceive('push')->once();

        $class = new SendsMessagesTraitTestClass($bot, $this->mock(User::class));
        $class->sendMessage($this->faker()->text, $this->mock(Keyboard::class));
    }
}

class SendsMessagesTraitTestClass
{
    use SendsMessages;

    protected $bot;
    private $user;

    public function __construct(Bot $bot, User $user)
    {
        $this->bot = $bot;
        $this->user = $user;
    }

    public function __call($name, $arguments)
    {
        return $this->$name(...$arguments);
    }

    /**
     * Get user.
     *
     * @return User
     */
    protected function user(): User
    {
        return $this->user;
    }
}
