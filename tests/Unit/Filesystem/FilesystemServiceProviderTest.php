<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Filesystem;

use FondBot\Tests\TestCase;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Adapter\NullAdapter;
use FondBot\Filesystem\FilesystemServiceProvider;

class FilesystemServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(FilesystemServiceProvider::class)->makePartial();
        $provider->shouldReceive('adapters')->andReturn(['null' => $adapter = new NullAdapter()])->once();

        $this->container->add('base_path', sys_get_temp_dir());
        $this->container->addServiceProvider($provider);

        /** @var MountManager $manager */
        $manager = $this->container->get(MountManager::class);

        $this->assertInstanceOf(MountManager::class, $manager);

        /** @var Filesystem $local */
        $local = $manager->getFilesystem('local');
        /** @var Filesystem $null */
        $null = $manager->getFilesystem('null');

        $this->assertInstanceOf(Local::class, $local->getAdapter());
        $this->assertInstanceOf(NullAdapter::class, $null->getAdapter());
    }
}
