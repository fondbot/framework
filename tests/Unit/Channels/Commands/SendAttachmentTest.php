<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Channels\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
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
