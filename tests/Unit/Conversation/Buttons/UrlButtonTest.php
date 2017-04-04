<?php

declare(strict_types=1);

namespace Tests\Unit\Conversation\Buttons;

use Tests\TestCase;
use FondBot\Conversation\Buttons\UrlButton;

class UrlButtonTest extends TestCase
{
    public function test()
    {
        $label = $this->faker()->word;
        $url = $this->faker()->url;

        $button = new UrlButton($label, $url);
        $this->assertSame($label, $button->getLabel());
        $this->assertSame($url, $button->getUrl());
    }
}
