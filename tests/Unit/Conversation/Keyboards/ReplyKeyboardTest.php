<?php
declare(strict_types=1);

namespace Tests\Unit\Conversation\Keyboards;

use FondBot\Conversation\Keyboards\Button;
use FondBot\Conversation\Keyboards\ReplyKeyboard;
use Tests\TestCase;

class ReplyKeyboardTest extends TestCase
{

    public function test_create()
    {
        $buttons = [
            Button::create('Click me')
        ];

        $keyboard = ReplyKeyboard::create($buttons);

        $this->assertInstanceOf(ReplyKeyboard::class, $keyboard);
        $this->assertEquals('reply', $keyboard->getType());
        $this->assertSame($buttons, $keyboard->getButtons());
        $this->assertEquals('Click me', $keyboard->getButtons()[0]->getValue());
    }

}