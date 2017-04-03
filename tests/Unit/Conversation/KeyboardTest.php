<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use FondBot\Conversation\Keyboard;
use FondBot\Conversation\Buttons\ReplyButton;

class KeyboardTest extends TestCase
{
    public function test_create()
    {
        $buttons = [
            new ReplyButton('Click me'),
        ];

        $keyboard = new Keyboard($buttons);

        $this->assertInstanceOf(Keyboard::class, $keyboard);
        $this->assertSame($buttons, $keyboard->getButtons());
        $this->assertEquals('Click me', $keyboard->getButtons()[0]->getLabel());
    }
}
