<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Controllers;

use FondBot\Tests\TestCase;
use FondBot\Foundation\Kernel;
use FondBot\Controllers\Controller;

class ControllerTest extends TestCase
{
    public function test(): void
    {
        $kernel = $this->mock(Kernel::class);

        $controller = new Controller($kernel);
        $response = $controller->__invoke();

        $this->assertRegExp('/FondBot v([0-9]+)\.([0-9]+)\.([0-9]+)/', (string) $response->getBody());
    }
}
