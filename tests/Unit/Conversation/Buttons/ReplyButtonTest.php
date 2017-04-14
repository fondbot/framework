<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Buttons;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Buttons\ReplyButton;

class ReplyButtonTest extends TestCase
{
    public function test()
    {
        $label = $this->faker()->word;

        $button = new ReplyButton($label);
        $this->assertSame($label, $button->getLabel());
    }
}
