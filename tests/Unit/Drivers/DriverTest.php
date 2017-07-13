<?php

declare(strict_types=1);

namespace FondBot\Tests\Unit\Drivers;

use Mockery\Mock;
use GuzzleHttp\Client;
use FondBot\Http\Request;
use FondBot\Drivers\Driver;
use FondBot\Tests\TestCase;
use FondBot\Drivers\Command;
use FondBot\Drivers\CommandHandler;

class DriverTest extends TestCase
{
    public function testHttp()
    {
        $guzzle = $this->mock(Client::class);

        /** @var Driver $driver */
        $driver = $this->mock(Driver::class, [$guzzle])->makePartial();

        $this->assertSame($guzzle, $driver->getHttp());
    }

    public function testFill(): void
    {
        $request = new Request([], []);

        /** @var Driver $driver */
        $driver = $this->mock(Driver::class)->makePartial();

        $driver->fill(['foo' => 'bar'], $request);

        $this->assertSame('bar', $driver->getParameter('foo'));
        $this->assertSame('z', $driver->getParameter('foo-bar', 'z'));
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
