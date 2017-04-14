<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation\Buttons;

use FondBot\Tests\TestCase;
use FondBot\Conversation\Buttons\UrlButton;

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
