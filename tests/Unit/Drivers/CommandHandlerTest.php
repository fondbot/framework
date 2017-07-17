<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use Mockery;
use Mockery\Mock;
use RuntimeException;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use FondBot\Drivers\CommandHandler;
use FondBot\Drivers\Commands\SendMessage;

class CommandHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
    }

    public function testMethodExists(): void
    {
        $driver = $this->mock(Driver::class);
        $command = $this->mock(SendMessage::class);
        /** @var CommandHandler|Mock $handler */
        $handler = $this->mock(CommandHandler::class, [$driver])->shouldAllowMockingProtectedMethods()->makePartial();

        $command->shouldReceive('getName')->andReturn('SendMessage')->once();
        $handler->shouldReceive('handleSendMessage')->once();

        $handler->handle($command);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage No handle method for "foo".
     */
    public function testMethodDoesNotExist(): void
    {
        $driver = $this->mock(Driver::class);
        $command = $this->mock(Command::class);
        /** @var CommandHandler|Mock $handler */
        $handler = $this->mock(CommandHandler::class, [$driver])->makePartial();

        $command->shouldReceive('getName')->andReturn('foo')->atLeast()->once();

        $handler->handle($command);
    }
}
