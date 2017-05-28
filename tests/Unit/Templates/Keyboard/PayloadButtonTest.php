<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Templates\Keyboard;

use FondBot\Tests\TestCase;
use FondBot\Templates\Keyboard\PayloadButton;

class PayloadButtonTest extends TestCase
{
    public function test()
    {
        $label = $this->faker()->word;
        $payload = $this->faker()->text;

        $button = (new PayloadButton)
            ->setLabel($label)
            ->setPayload($payload);

        $this->assertSame('PayloadButton', $button->getName());
        $this->assertSame($label, $button->getLabel());
        $this->assertSame($payload, $button->getPayload());
    }
}
