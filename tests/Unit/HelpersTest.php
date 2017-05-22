<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit;

use FondBot\Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_env(): void
    {
        $_ENV['foo'] = 'bar';
        $this->assertSame('bar', env('foo'));

        $_ENV['foo'] = 'true';
        $this->assertTrue(env('foo'));

        $_ENV['foo'] = 'false';
        $this->assertFalse(env('foo'));

        $_ENV['foo'] = 'null';
        $this->assertNull(env('foo'));

        $this->assertNull(env('x'));
        $this->assertSame('y', env('x', 'y'));
    }
}
