<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use FondBot\Http\Request;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;

class DriverTest extends TestCase
{
    public function test_fill(): void
    {
        $request = new Request([], []);

        /** @var Driver $driver */
        $driver = $this->mock(Driver::class)->makePartial();

        $driver->fill(['foo' => 'bar'], $request);

        $this->assertSame('bar', $driver->getParameter('foo'));
        $this->assertSame('z', $driver->getParameter('foo-bar', 'z'));
    }
}
