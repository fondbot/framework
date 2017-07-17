<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use Mockery\Mock;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use FondBot\Channels\Channel;
use FondBot\Drivers\CommandHandler;
use Psr\Http\Message\RequestInterface;

class DriverTest extends TestCase
{
    public function testInitialize(): void
    {
        $channel = $this->mock(Channel::class);
        $request = $this->mock(RequestInterface::class);

        /** @var Driver|Mock $driver */
        $driver = $this->mock(Driver::class)->makePartial();

        $channel->shouldReceive('getParameters')->andReturn(['foo' => 'bar'])->atLeast()->once();
        $driver->shouldReceive('getDefaultParameters')->andReturn(['foo' => '', 'bar' => ''])->once();

        $driver = $driver->initialize($channel, $request);

        $this->assertSame('bar', $driver->getParameters()['foo']);
        $this->assertSame('', $driver->getParameters()['bar']);
    }

    public function testHandle(): void
    {
        $command = $this->mock(Command::class);
        $commandHandler = $this->mock(CommandHandler::class);

        /** @var Driver|Mock $driver */
        $driver = $this->mock(Driver::class)->makePartial();

        $driver->shouldReceive('getCommandHandler')->andReturn($commandHandler)->once();
        $commandHandler->shouldReceive('handle')->with($command)->once();

        $driver->handle($command);
    }
}
