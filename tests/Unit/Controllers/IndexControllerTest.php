<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Controllers;

use FondBot\Tests\TestCase;
use FondBot\Controllers\IndexController;

class IndexControllerTest extends TestCase
{
    public function test(): void
    {
        $controller = new IndexController($this->kernel);
        $response = $controller->show();

        $this->assertRegExp('/FondBot v.*/', $response);
    }
}
