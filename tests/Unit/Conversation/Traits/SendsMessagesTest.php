<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use FondBot\Foundation\Kernel;
use FondBot\Templates\Keyboard;
use FondBot\Templates\Attachment;
use Illuminate\Contracts\Bus\Dispatcher;
use FondBot\Drivers\Commands\SendMessage;
use FondBot\Drivers\Commands\SendRequest;
use FondBot\Drivers\Commands\SendAttachment;
use Illuminate\Support\Testing\Fakes\BusFake;
use FondBot\Conversation\Traits\SendsMessages;

class SendsMessagesTest extends TestCase
{
    public function testSendMessage(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendMessage($this->faker()->text, $this->mock(Keyboard::class));

        $dispatcher->assertDispatched(SendMessage::class);
    }

    public function testSendMessageWithDelay(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendMessage($this->faker()->text, $this->mock(Keyboard::class), random_int(1, 10));

        $dispatcher->assertDispatched(SendMessage::class);
    }

    public function testSendAttachment(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendAttachment($this->mock(Attachment::class));

        $dispatcher->assertDispatched(SendAttachment::class);
    }

    public function testSendAttachmentWithDelay(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendAttachment($this->mock(Attachment::class), random_int(1, 10));

        $dispatcher->assertDispatched(SendAttachment::class);
    }

    public function testSendRequest(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->kernel->setChannel($this->mock(Channel::class));
        $this->kernel->setDriver($this->mock(Driver::class));

        $class = new SendsMessagesTraitTestClass($this->kernel, $this->mock(Chat::class), $this->mock(User::class));
        $class->sendRequest('endpoint', ['foo' => 'bar']);

        $dispatcher->assertDispatched(SendRequest::class);
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
