<?php

declare(strict_types=1);

namespace Tests\Unit\Filesystem;

use FondBot\Tests\TestCase;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\NullAdapter;
use FondBot\Filesystem\FilesystemServiceProvider;

class FilesystemServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(FilesystemServiceProvider::class)->makePartial();
        $provider->shouldReceive('adapter')->andReturn($adapter = new NullAdapter())->once();

        $this->container->addServiceProvider($provider);

        /** @var Filesystem $filesystem */
        $filesystem = $this->container->get(Filesystem::class);

        $this->assertInstanceOf(Filesystem::class, $filesystem);
        $this->assertSame($adapter, $filesystem->getAdapter());
    }
}
