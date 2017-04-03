<?php

declare(strict_types=1);

namespace Tests\Unit\Channels\Telegram;

use Tests\TestCase;
use FondBot\Conversation\Keyboard;
use FondBot\Contracts\Channels\User;
use FondBot\Conversation\Buttons\UrlButton;
use FondBot\Conversation\Buttons\ReplyButton;
use FondBot\Conversation\Buttons\PayloadButton;
use FondBot\Channels\Telegram\TelegramOutgoingMessage;
use FondBot\Channels\Telegram\Buttons\RequestContactButton;

/**
 * @property mixed|\Mockery\Mock user
 */
class TelegramOutgoingMessageTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->user = $this->mock(User::class);
    }

    public function test_getRecipient()
    {
        $message = new TelegramOutgoingMessage($this->user, '');

        $this->assertSame($this->user, $message->getRecipient());
    }

    public function test_getText()
    {
        $message = new TelegramOutgoingMessage($this->user, $text = $this->faker()->text);

        $this->assertSame($text, $message->getText());
    }

    public function test_getKeyboard()
    {
        $message = new TelegramOutgoingMessage($this->user, '', $keyboard = $this->mock(Keyboard::class));

        $this->assertSame($keyboard, $message->getKeyboard());
    }

    public function test_toArray_without_keyboard()
    {
        $userId = $this->faker()->uuid;
        $text = $this->faker()->text;

        $this->user->shouldReceive('getId')->andReturn($userId)->atLeast()->once();

        $message = new TelegramOutgoingMessage($this->user, $text);

        $expected = [
            'chat_id' => $userId,
            'text' => $text,
        ];

        $this->assertSame($expected, $message->toArray());
    }

    public function test_toArray_with_reply_keyboard()
    {
        $userId = $this->faker()->uuid;
        $text = $this->faker()->text;
        $keyboard = new Keyboard([
            $button1 = new ReplyButton($this->faker()->word),
            $button2 = new ReplyButton($this->faker()->word),
            $button3 = new RequestContactButton($this->faker()->word),
        ]);

        $this->user->shouldReceive('getId')->andReturn($userId)->atLeast()->once();

        $message = new TelegramOutgoingMessage($this->user, $text, $keyboard);

        $expected = [
            'chat_id' => $userId,
            'text' => $text,
            'reply_markup' => json_encode([
                'keyboard' => [[
                    ['text' => $button1->getLabel()],
                    ['text' => $button2->getLabel()],
                    ['text' => $button3->getLabel(), 'request_contact' => true],
                ]],
                'one_time_keyboard' => true,
            ]),
        ];

        $this->assertSame($expected, $message->toArray());
    }

    public function test_toArray_with_inline_keyboard()
    {
        $userId = $this->faker()->uuid;
        $text = $this->faker()->text;
        $keyboard = new Keyboard([
            $button1 = new UrlButton($this->faker()->word, 'https://fondbot.com'),
            $button2 = new PayloadButton($this->faker()->word, 'do-something'),
            $button3 = new PayloadButton($this->faker()->word, ['action' => 'something']),
        ]);

        $this->user->shouldReceive('getId')->andReturn($userId)->atLeast()->once();

        $message = new TelegramOutgoingMessage($this->user, $text, $keyboard);

        $expected = [
            'chat_id' => $userId,
            'text' => $text,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => $button1->getLabel(), 'url' => $button1->getUrl()],
                    ['text' => $button2->getLabel(), 'callback_data' => $button2->getPayload()],
                    ['text' => $button3->getLabel(), 'callback_data' => $button3->getPayload()],
                ]],
            ]),
        ];

        $this->assertSame($expected, $message->toArray());
    }
}
