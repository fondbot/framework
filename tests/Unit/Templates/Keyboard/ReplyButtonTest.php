<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Templates\Keyboard;

use FondBot\Tests\TestCase;
use FondBot\Templates\Keyboard\ReplyButton;

class ReplyButtonTest extends TestCase
{
    public function test()
    {
        $label = $this->faker()->word;

        $button = ReplyButton::create($label);

        $this->assertSame('ReplyButton', $button->getName());
        $this->assertSame($label, $button->getLabel());
    }
}
