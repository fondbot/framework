<?php

declare(strict_types=1);

namespace Tests\Unit\Filesystem\Providers;

use FondBot\Tests\TestCase;
use FondBot\Filesystem\Filesystem;
use League\Flysystem\Adapter\NullAdapter;
use FondBot\Filesystem\Providers\FilesystemServiceProvider;

class FilesystemProviderTest extends TestCase
{
    public function test(): void
    {
        $adapter = new NullAdapter();

        $this->container->addServiceProvider(new FilesystemServiceProvider($adapter));

        /** @var Filesystem $filesystem */
        $filesystem = $this->container->get(Filesystem::class);

        $this->assertInstanceOf(Filesystem::class, $filesystem);

        $this->assertSame($adapter, $filesystem->getAdapter());
    }
}
