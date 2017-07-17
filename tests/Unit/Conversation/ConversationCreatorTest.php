<?php
declare(strict_types=1);

namespace FondBot\Tests\Unit\Conversation;

use FondBot\Conversation\ConversationCreator;
use FondBot\Tests\TestCase;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\MountManager;

class ConversationCreatorTest extends TestCase
{
    /**
     * @var ConversationCreator
     */
    protected $conversationCreator;

    /**
     * @var FilesystemInterface
     */
    protected $fileSystem;

    /**
     * @var string
     */
    protected $loadString;

    public function setUp() : void
    {
        parent::setUp();
        $mountManager = $this->mock(MountManager::class);
        $this->fileSystem = $this->mock(FilesystemInterface::class);
        $mountManager->shouldReceive('getFilesystem')->once()->with('local')->andReturn($this->fileSystem);
        $this->loadString = $this->faker()->randomLetter . '\\\\';
        $this->conversationCreator = new ConversationCreator($mountManager);
    }

    public function test_createIntent() : void
    {
        $this->fileSystem->shouldReceive('read')->once()
            ->with('vendor/fondbot/framework/resources/stubs/Intent.stub')->andReturn($this->loadString);
        $this->fileSystem->shouldReceive('write')->once();
        $this->conversationCreator->createIntent($this->loadString, $this->loadString, $this->loadString);
    }

    public function test_createInteraction() : void
    {
        $this->fileSystem->shouldReceive('read')->once()
            ->with('vendor/fondbot/framework/resources/stubs/Interaction.stub')->andReturn($this->loadString);
        $this->fileSystem->shouldReceive('write')->once();
        $this->conversationCreator->createInteraction($this->loadString, $this->loadString, $this->loadString);
    }

}