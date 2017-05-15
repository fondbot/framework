<?php

declare(strict_types=1);

namespace Tests\Unit\Cache\Adapters;

use FondBot\Tests\TestCase;
use League\Flysystem\Filesystem;
use League\Flysystem\FileNotFoundException;
use FondBot\Cache\Adapters\FilesystemAdapter;

class FilesystemAdapterTest extends TestCase
{
    public function test_get(): void
    {
        $filesystem = $this->mock(Filesystem::class);
        $adapter = new FilesystemAdapter($filesystem);

        $filesystem->shouldReceive('read')->with(md5('foo'))->andReturn('bar')->once();

        $this->assertSame('bar', $adapter->get('foo'));

        $filesystem->shouldReceive('read')->with(md5('bar'))->andThrow(new FileNotFoundException(md5('bar')))->once();

        $this->assertSame('value', $adapter->get('bar', 'value'));
    }

    public function test_store(): void
    {
        $filesystem = $this->mock(Filesystem::class);
        $adapter = new FilesystemAdapter($filesystem);
        $array = ['foo' => 'bar'];
        $collection = collect($array);

        $filesystem->shouldReceive('put')->with(md5('foo'), json_encode($array))->once();
        $filesystem->shouldReceive('put')->with(md5('bar'), json_encode($collection))->once();

        $adapter->store('foo', $array);
        $adapter->store('bar', $collection);
    }

    public function test_forget(): void
    {
        $filesystem = $this->mock(Filesystem::class);
        $adapter = new FilesystemAdapter($filesystem);

        $filesystem->shouldReceive('delete')->with(md5('foo'))->once();

        $adapter->forget('foo');
    }
}
