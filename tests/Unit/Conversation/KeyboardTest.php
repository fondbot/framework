<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation;

use Tests\TestCase;
use FondBot\Conversation\Keyboard;
use FondBot\Conversation\Buttons\Button;

class KeyboardTest extends TestCase
{
    public function test_create()
    {
        $buttons = [
            $this->mock(Button::class),
            $this->mock(Button::class),
        ];

        $keyboard = new Keyboard($buttons);

        $this->assertInstanceOf(Keyboard::class, $keyboard);
        $this->assertSame($buttons, $keyboard->getButtons());
    }
}
