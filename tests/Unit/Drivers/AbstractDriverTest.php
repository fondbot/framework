<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use Mockery\Mock;
use FondBot\Http\Request;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use FondBot\Drivers\AbstractDriver;
use FondBot\Drivers\CommandHandler;

class AbstractDriverTest extends TestCase
{
    public function test_initialize(): void
    {
        $request = $this->mock(Request::class);

        /** @var AbstractDriver|Mock $driver */
        $driver = $this->mock(AbstractDriver::class)->makePartial();
        $driver->shouldReceive('getDefaultParameters')->andReturn(['foo' => '', 'bar' => ''])->once();

        $driver = $driver->initialize(['foo' => 'bar'], $request);

        $this->assertSame('bar', $driver->getParameters()['foo']);
        $this->assertSame('', $driver->getParameters()['bar']);
    }

    public function test_handle(): void
    {
        $command = $this->mock(Command::class);
        $commandHandler = $this->mock(CommandHandler::class);

        /** @var AbstractDriver|Mock $driver */
        $driver = $this->mock(AbstractDriver::class)->makePartial();

        $driver->shouldReceive('getCommandHandler')->andReturn($commandHandler)->once();
        $commandHandler->shouldReceive('handle')->with($command)->once();

        $driver->handle($command);
    }
}
