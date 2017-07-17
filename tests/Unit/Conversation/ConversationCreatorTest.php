<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Tests\TestCase;
use League\Flysystem\MountManager;
use League\Flysystem\FilesystemInterface;
use FondBot\Conversation\ConversationCreator;

class ConversationCreatorTest extends TestCase
{
    public function testCreateIntent(): void
    {
        $mountManager = $this->mock(MountManager::class);
        $filesystem = $this->mock(FilesystemInterface::class);
        $mountManager->shouldReceive('getFilesystem')->once()->with('local')->andReturn($filesystem);

        $filesystem->shouldReceive('read')->once()->with('vendor/fondbot/framework/resources/stubs/Intent.stub')->andReturn('foo');
        $filesystem->shouldReceive('write')->once();

        (new ConversationCreator($mountManager))->createIntent('foo', 'bar', 'baz');
    }

    public function testCreateInteraction(): void
    {
        $mountManager = $this->mock(MountManager::class);
        $filesystem = $this->mock(FilesystemInterface::class);
        $mountManager->shouldReceive('getFilesystem')->once()->with('local')->andReturn($filesystem);

        $filesystem->shouldReceive('read')->once()->with('vendor/fondbot/framework/resources/stubs/Interaction.stub')->andReturn('foo');
        $filesystem->shouldReceive('write')->once();

        (new ConversationCreator($mountManager))->createInteraction('foo', 'bar', 'baz');
    }
}
