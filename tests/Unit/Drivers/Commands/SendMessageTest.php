<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Tests\TestCase;
use InvalidArgumentException;
use FondBot\Contracts\Template;
use FondBot\Foundation\Commands\SendMessage;

class SendMessageTest extends TestCase
{
    public function test(): void
    {
        $chat = $this->mock(Chat::class);
        $recipient = $this->mock(User::class);
        $text = $this->faker()->text;
        $template = $this->mock(Template::class);

        $command = new SendMessage($chat, $recipient, $text, $template);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Either text or template should be set.
     */
    public function testTextAndTemplateNull()
    {
        new SendMessage(
            $this->mock(Chat::class),
            $this->mock(User::class)
        );
    }
}
