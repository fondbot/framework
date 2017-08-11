<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Tests\TestCase;
use FondBot\Templates\Attachment;
use FondBot\Foundation\Commands\SendAttachment;

class SendAttachmentTest extends TestCase
{
    public function test(): void
    {
        $chat = $this->mock(Chat::class);
        $recipient = $this->mock(User::class);
        $attachment = $this->mock(Attachment::class);

        $command = new SendAttachment($chat, $recipient, $attachment);
    }
}
