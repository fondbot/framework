<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Templates;

use FondBot\Tests\TestCase;
use FondBot\Templates\Keyboard;
use FondBot\Templates\Keyboard\Button;

class KeyboardTest extends TestCase
{
    public function test_create()
    {
        $buttons = [
            $this->mock(Button::class),
            $this->mock(Button::class),
        ];

        $keyboard = (new Keyboard)
            ->addButton($buttons[0])
            ->addButton($buttons[1]);

        $this->assertInstanceOf(Keyboard::class, $keyboard);
        $this->assertSame($buttons, $keyboard->getButtons());
        $this->assertSame(['buttons' => $buttons], $keyboard->toArray());
        $this->assertSame(['buttons' => $buttons], $keyboard->jsonSerialize());
    }
}
