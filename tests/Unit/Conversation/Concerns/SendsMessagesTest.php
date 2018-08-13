<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Concerns;

use FondBot\Tests\TestCase;
use FondBot\Contracts\Template;
use FondBot\Conversation\Context;
use FondBot\Templates\Attachment;
use Illuminate\Support\Facades\Bus;
use FondBot\Foundation\Commands\SendMessage;
use FondBot\Foundation\Commands\SendAttachment;
use FondBot\Conversation\Concerns\SendsMessages;

class SendsMessagesTest extends TestCase
{
    use SendsMessages;

    protected function setUp(): void
    {
        parent::setUp();

        $context = $this->mock(Context::class);
        $context->shouldReceive('getChannel')->atLeast()->once();
        $context->shouldReceive('getChat')->atLeast()->once();
        $context->shouldReceive('getUser')->atLeast()->once();

        $this->setContext($context);
    }

    public function testReply(): void
    {
        Bus::fake();

        $this->reply($this->faker()->text)->template($this->mock(Template::class));

        Bus::assertDispatched(SendMessage::class);
    }

    public function testReplyWithDelay(): void
    {
        Bus::fake();

        $this->reply($this->faker()->text)
            ->template($this->mock(Template::class))
            ->delay(5);

        Bus::assertDispatched(SendMessage::class, function (SendMessage $job) {
            return $job->delay === 5;
        });
    }

    public function testSendAttachment(): void
    {
        Bus::fake();

        $this->sendAttachment($this->mock(Attachment::class));

        Bus::assertDispatched(SendAttachment::class);
    }

    public function testSendAttachmentWithDelay(): void
    {
        Bus::fake();

        $this->sendAttachment($this->mock(Attachment::class))->delay(7);

        Bus::assertDispatched(SendAttachment::class, function (SendAttachment $job) {
            return $job->delay === 7;
        });
    }
}
