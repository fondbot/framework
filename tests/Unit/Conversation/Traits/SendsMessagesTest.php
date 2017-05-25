<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Contracts\Queue;
use FondBot\Channels\Channel;
use FondBot\Application\Kernel;
use FondBot\Templates\Keyboard;
use FondBot\Templates\Attachment;
use FondBot\Conversation\Traits\SendsMessages;

class SendsMessagesTest extends TestCase
{
    public function test_sendMessage(): void
    {
        $queue = $this->mock(Queue::class);
        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $queue->shouldReceive('push')->once();

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendMessage($this->faker()->text, $this->mock(Keyboard::class));
    }

    public function test_sendDelayedMessage(): void
    {
        $queue = $this->mock(Queue::class);
        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $queue->shouldReceive('later')->once();

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendDelayedMessage(random_int(1, 10), $this->faker()->text, $this->mock(Keyboard::class));
    }

    public function test_sendAttachment(): void
    {
        $queue = $this->mock(Queue::class);
        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $queue->shouldReceive('push')->once();

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendAttachment($this->mock(Attachment::class));
    }

    public function test_sendAttachment_with_delay(): void
    {
        $queue = $this->mock(Queue::class);
        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $queue->shouldReceive('later')->once();

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendAttachment($this->mock(Attachment::class), random_int(1, 10));
    }

    public function test_sendRequest(): void
    {
        $queue = $this->mock(Queue::class);
        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $queue->shouldReceive('push')->once();

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendRequest('endpoint', ['foo' => 'bar']);
    }
}

class SendsMessagesTraitTestClass
{
    use SendsMessages;

    protected $kernel;
    private $chat;
    private $user;

    public function __construct(Kernel $kernel, Chat $chat, User $user)
    {
        $this->kernel = $kernel;
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
