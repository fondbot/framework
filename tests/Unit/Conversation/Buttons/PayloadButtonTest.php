<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Buttons;

use Tests\TestCase;
use FondBot\Conversation\Buttons\PayloadButton;

class PayloadButtonTest extends TestCase
{
    public function test()
    {
        $label = $this->faker()->word;
        $payload = $this->faker()->text;

        $button = new PayloadButton($label, $payload);
        $this->assertSame($label, $button->getLabel());
        $this->assertSame($payload, $button->getPayload());
    }
}
