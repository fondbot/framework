<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Traits;

use FondBot\Bot;
use Tests\TestCase;
use FondBot\Drivers\User;
use FondBot\Conversation\Keyboard;
use FondBot\Conversation\Traits\SendsMessages;

class SendsMessagesTest extends TestCase
{
    public function test_sendMessage()
    {
        $bot = $this->mock(Bot::class);

        $bot->shouldReceive('sendMessage')
            ->with(
                $user = $this->mock(User::class),
                $text = $this->faker()->text,
                $keyboard = $this->mock(Keyboard::class),
                $driver = $this->faker()->word
            )
            ->once();

        $class = new SendsMessagesTraitTestClass($bot, $user);
        $class->sendMessage($text, $keyboard, $driver);
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
