<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Keyboards;

use Tests\TestCase;
use FondBot\Conversation\Keyboards\Button;
use FondBot\Conversation\Keyboards\BasicKeyboard;

class BasicKeyboardTest extends TestCase
{
    public function test_create()
    {
        $buttons = [
            new Button('Click me'),
        ];

        $keyboard = new BasicKeyboard($buttons);

        $this->assertInstanceOf(BasicKeyboard::class, $keyboard);
        $this->assertEquals('basic', $keyboard->getType());
        $this->assertSame($buttons, $keyboard->getButtons());
        $this->assertEquals('Click me', $keyboard->getButtons()[0]->getLabel());
    }
}
