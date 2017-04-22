<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Templates\Keyboard;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Templates\Keyboard\UrlButton;

class UrlButtonTest extends TestCase
{
    public function test()
    {
        $label = $this->faker()->word;
        $url = $this->faker()->url;
        $params = [
            'args1' => $this->faker()->word,
            'args2' => $this->faker()->word,
        ];

        $button = new UrlButton($label, $url, $params);
        $this->assertSame($label, $button->getLabel());
        $this->assertSame($url, $button->getUrl());
        $this->assertSame($params, $button->getParameters());
    }
}
