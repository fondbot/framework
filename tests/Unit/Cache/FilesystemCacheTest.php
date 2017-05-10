<?php

declare(strict_types=1);

namespace Tests\Unit\Cache;

use FondBot\Tests\TestCase;
use League\Flysystem\Filesystem;
use FondBot\Cache\FilesystemCache;

class FilesystemCacheTest extends TestCase
{
    public function test_get(): void
    {
        $filesystem = $this->mock(Filesystem::class);
        $cache = new FilesystemCache($filesystem);

        $filesystem->shouldReceive('get')->with(md5('foo'))->andReturn('bar')->once();

        $this->assertSame('bar', $cache->get('foo'));

        $filesystem->shouldReceive('get')->with(md5('bar'))->andReturnNull()->once();

        $this->assertSame('value', $cache->get('bar', 'value'));
    }

    public function test_store(): void
    {
        $filesystem = $this->mock(Filesystem::class);
        $cache = new FilesystemCache($filesystem);
        $array = ['foo' => 'bar'];
        $collection = collect($array);

        $filesystem->shouldReceive('put')->with(md5('foo'), json_encode($array))->once();
        $filesystem->shouldReceive('put')->with(md5('bar'), json_encode($collection))->once();

        $cache->store('foo', $array);
        $cache->store('bar', $collection);
    }

    public function test_forget(): void
    {
        $filesystem = $this->mock(Filesystem::class);
        $cache = new FilesystemCache($filesystem);

        $filesystem->shouldReceive('delete')->with(md5('foo'))->once();

        $cache->forget('foo');
    }
}
