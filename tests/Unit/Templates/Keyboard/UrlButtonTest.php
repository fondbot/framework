<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Templates\Keyboard;

use FondBot\Tests\TestCase;
use FondBot\Templates\Keyboard\UrlButton;

class UrlButtonTest extends TestCase
{
    public function test()
    {
        $label = $this->faker()->word;
        $url = $this->faker()->url;
        $parameters = ['foo' => 'bar'];

        $button = UrlButton::create($label, $url, $parameters);

        $this->assertSame('UrlButton', $button->getName());
        $this->assertSame($label, $button->getLabel());
        $this->assertSame($url, $button->getUrl());
        $this->assertEquals(collect($parameters), $button->getParameters());
    }
}
