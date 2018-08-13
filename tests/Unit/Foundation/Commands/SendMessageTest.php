<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation\Commands;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Tests\TestCase;
use FondBot\Channels\Channel;
use InvalidArgumentException;
use FondBot\Contracts\Template;
use FondBot\Foundation\Commands\SendMessage;

class SendMessageTest extends TestCase
{
    public function test(): void
    {
        $channel = $this->mock(Channel::class);
        $chat = $this->mock(Chat::class);
        $recipient = $this->mock(User::class);
        $text = $this->faker()->text;
        $template = $this->mock(Template::class);

        new SendMessage($channel, $chat, $recipient, $text, $template);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Either text or template should be set.
     */
    public function testTextAndTemplateNull(): void
    {
        new SendMessage(
            $this->mock(Channel::class),
            $this->mock(Chat::class),
            $this->mock(User::class)
        );
    }
}
