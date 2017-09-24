<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Tests\TestCase;
use FondBot\Templates\Keyboard;
use FondBot\Tests\Mocks\FakeType;

class TypeTest extends TestCase
{
    public function testCreateFromTemplate(): void
    {
        $template1 = Keyboard::create();
        $template2 = Keyboard::create();

        // create one
        $result = FakeType::createFromTemplate($template1);

        $this->assertSame($template1, $result->template);

        $result = FakeType::createFromTemplate(compact('template1', 'template2'));

        $this->assertArrayHasKey('template1', $result);
        $this->assertSame($template1, $result['template1']->template);
        $this->assertArrayHasKey('template2', $result);
        $this->assertSame($template2, $result['template2']->template);
    }
}
