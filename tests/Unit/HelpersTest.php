<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit;

use FondBot\Tests\TestCase;

class HelpersTest extends TestCase
{
    public function testKernel(): void
    {
        $this->assertSame($this->kernel, kernel());
    }
}
