<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Templates;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Templates\Keyboard;
use FondBot\Conversation\Templates\Keyboard\Button;

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
