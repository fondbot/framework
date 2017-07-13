<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Cache\Adapters;

use FondBot\Tests\TestCase;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use FondBot\Cache\Adapters\FilesystemAdapter;

class FilesystemAdapterTest extends TestCase
{
    public function testGet(): void
    {
        $filesystem = new Filesystem(new MemoryAdapter);
        $adapter = new FilesystemAdapter($filesystem);

        // JSON
        $adapter->set('x', json_encode(['foo' => 'bar']));
        $this->assertSame(['foo' => 'bar'], $adapter->get('x'));

        // Not JSON
        $adapter->set('foo', 'bar');
        $this->assertSame('bar', $adapter->get('foo'));
    }

    public function testStore(): void
    {
        $filesystem = new Filesystem(new MemoryAdapter);
        $adapter = new FilesystemAdapter($filesystem);
        $array = ['foo' => 'bar'];
        $collection = collect($array);

        $adapter->store('foo', $array);
        $adapter->store('bar', $collection);

        $this->assertTrue($adapter->has('foo'));
        $this->assertTrue($adapter->has('bar'));
        $this->assertSame($array, $adapter->get('foo'));
        $this->assertSame($collection->toArray(), $adapter->get('bar'));
    }

    public function testForget(): void
    {
        $filesystem = new Filesystem(new MemoryAdapter);
        $adapter = new FilesystemAdapter($filesystem);

        $adapter->set('foo', 'x');
        $adapter->set('bar', 'y');

        $this->assertTrue($adapter->has('foo'));
        $this->assertTrue($adapter->has('bar'));

        $adapter->forget('bar');
        $adapter->forget('foo');

        $this->assertFalse($adapter->has('foo'));
        $this->assertFalse($adapter->has('bar'));
    }
}
