<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Traits;

use FondBot\Tests\TestCase;
use FondBot\Contracts\Template;
use FondBot\Conversation\Session;
use FondBot\Templates\Attachment;
use Illuminate\Contracts\Bus\Dispatcher;
use FondBot\Foundation\Commands\SendMessage;
use FondBot\Foundation\Commands\SendRequest;
use Illuminate\Support\Testing\Fakes\BusFake;
use FondBot\Conversation\Traits\SendsMessages;
use FondBot\Foundation\Commands\SendAttachment;

class SendsMessagesTest extends TestCase
{
    use SendsMessages;

    protected function setUp(): void
    {
        parent::setUp();

        $session = $this->mock(Session::class);
        $session->shouldReceive('getChat')->atLeast()->once();
        $session->shouldReceive('getUser')->atLeast()->once();

        $this->setSession($session);
    }

    public function testSendMessage(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->sendMessage($this->faker()->text, $this->mock(Template::class));

        $dispatcher->assertDispatched(SendMessage::class);
    }

    public function testSendMessageWithDelay(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->sendMessage($this->faker()->text, $this->mock(Template::class), random_int(1, 10));

        $dispatcher->assertDispatched(SendMessage::class);
    }

    public function testSendAttachment(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->sendAttachment($this->mock(Attachment::class));

        $dispatcher->assertDispatched(SendAttachment::class);
    }

    public function testSendAttachmentWithDelay(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->sendAttachment($this->mock(Attachment::class), random_int(1, 10));

        $dispatcher->assertDispatched(SendAttachment::class);
    }

    public function testSendRequest(): void
    {
        /** @var BusFake $dispatcher */
        $dispatcher = $this->container->make(Dispatcher::class);

        $this->sendRequest('endpoint', ['foo' => 'bar']);

        $dispatcher->assertDispatched(SendRequest::class);
    }
}
