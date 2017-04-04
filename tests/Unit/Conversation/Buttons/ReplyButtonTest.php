<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Buttons;

use FondBot\Conversation\Buttons\ReplyButton;
use Tests\TestCase;

class ReplyButtonTest extends TestCase
{
    public function test()
    {
        $label = $this->faker()->word;

        $button = new ReplyButton($label);
        $this->assertSame($label, $button->getLabel());
    }
}
