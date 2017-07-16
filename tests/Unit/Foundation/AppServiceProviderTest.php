<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Foundation;

use FondBot\Tests\TestCase;
use FondBot\Foundation\AppServiceProvider;
use FondBot\Conversation\ConversationManager;

class AppServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $provider = $this->mock(AppServiceProvider::class)->makePartial();

        $basePath = sys_get_temp_dir();
        $resourcesPath = $basePath.'/resources';

        file_put_contents($basePath.'/.env', 'FOO=BAR');

        $provider->shouldReceive('environment')->andReturn('production')->once();
        $provider->shouldReceive('basePath')->andReturn($basePath)->twice();
        $provider->shouldReceive('resourcesPath')->andReturn($resourcesPath)->once();

        $this->container->addServiceProvider($provider);

        $this->assertSame('BAR', env('FOO'));
        $this->assertSame('production', $this->container->get('environment'));
        $this->assertSame($basePath, $this->container->get('base_path'));
        $this->assertSame($resourcesPath, $this->container->get('resources_path'));
        $this->assertInstanceOf(ConversationManager::class, $this->container->get(ConversationManager::class));

        unlink($basePath.'/.env');
    }

    public function testDotenvInvalidPathException(): void
    {
        $provider = $this->mock(AppServiceProvider::class)->makePartial();

        $basePath = sys_get_temp_dir();
        $resourcesPath = $basePath.'/resources';

        $provider->shouldReceive('environment')->andReturn('production')->once();
        $provider->shouldReceive('basePath')->andReturn($basePath)->twice();
        $provider->shouldReceive('resourcesPath')->andReturn($resourcesPath)->once();

        $this->container->addServiceProvider($provider);

        $this->assertSame('BAR', env('FOO'));
        $this->assertSame('production', $this->container->get('environment'));
        $this->assertSame($basePath, $this->container->get('base_path'));
        $this->assertSame($resourcesPath, $this->container->get('resources_path'));
    }
}
