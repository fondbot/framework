<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Controllers;

use FondBot\Tests\TestCase;
use FondBot\Controllers\Controller;

class ControllerTest extends TestCase
{
    public function test(): void
    {
        $controller = new Controller($this->kernel);
        $response = $controller->run();

        $this->assertRegExp('/FondBot v([0-9]+)\.([0-9]+)\.([0-9]+)/', (string) $response->getBody());
    }
}
