<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Application;

use FondBot\Tests\TestCase;
use FondBot\Application\Config;

class ConfigTest extends TestCase
{
    public function test(): void
    {
        $config = new Config([]);
        $config->set('foo', 'bar');

        $this->assertSame('bar', $config->get('foo'));

        $config->set('foo.bar', 'baz');

        $this->assertSame(['bar' => 'baz'], $config->get('foo'));
        $this->assertSame('baz', $config->get('foo.bar'));
        $this->assertTrue($config->has('foo'));
        $this->assertTrue($config->has('foo.bar'));
        $this->assertFalse($config->has('bar'));
        $this->assertFalse($config->has('bar.foo'));

        $this->assertSame(['foo' => ['bar' => 'baz']], $config->jsonSerialize());
    }
}
