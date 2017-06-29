<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers\Commands;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Tests\TestCase;
use InvalidArgumentException;
use FondBot\Contracts\Template;
use FondBot\Drivers\Commands\SendMessage;

class SendMessageTest extends TestCase
{
    public function test(): void
    {
        $chat = $this->mock(Chat::class);
        $recipient = $this->mock(User::class);
        $text = $this->faker()->text;
        $template = $this->mock(Template::class);

        $command = new SendMessage($chat, $recipient, $text, $template);

        $this->assertSame('SendMessage', $command->getName());
        $this->assertSame($chat, $command->getChat());
        $this->assertSame($recipient, $command->getRecipient());
        $this->assertSame($text, $command->getText());
        $this->assertSame($template, $command->getTemplate());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Either text or template should be set.
     */
    public function test_text_and_template_null()
    {
        new SendMessage(
            $this->mock(Chat::class),
            $this->mock(User::class)
        );
    }
}
